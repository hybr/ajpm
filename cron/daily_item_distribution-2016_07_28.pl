#!/bin/perl -w

use strict;
use warnings;
use Data::Dumper; 
use MongoDB;
use MongoDB::OID;

my $debug = 0;
my $debugRecord = 0;

sub getRate {
	my ($rateAmount, $rateQuantity) = @_;
	my $perUnitRate = 0;
	if ($rateQuantity > 0) {
		$perUnitRate = $rateAmount / $rateQuantity;
	}
	return $perUnitRate;
}

sub getRateFromDoc {
	my ($doc) = @_;
	my $perUnitRate = 0;
	if (defined $doc 
		&& defined $doc->{'rate_quantity'} && $doc->{'rate_quantity'} > 0
		&& defined $doc->{'rate_amount'} && $doc->{'rate_amount'} > 0
	) {
		$perUnitRate = $doc->{'rate_amount'} / $doc->{'rate_quantity'};
	} else {
		# this function is used when we calculate rate from the price field of item table
		if (defined $doc 
			&& defined $doc->{'per'} && $doc->{'per'} > 0
			&& defined $doc->{'amount'} && $doc->{'amount'} > 0
		) {
			$perUnitRate = $doc->{'amount'} / $doc->{'per'};
		}
	}
	return $perUnitRate;
}

sub getDailyCost {
	# daily cost calculation
	my ($rateAmount, $rateQuantity, $dailyQuantity) = @_;
	return $dailyQuantity * getRate($rateAmount, $rateQuantity);
}

sub getDailyCostFromDoc {
	# daily cost calculation
	# item price subRecord does not have daily quantity
	my ($quantityDoc, $rateDoc) = @_;
	my $cost = 0;

	my $rate = 0;
	$rate = getRateFromDoc($rateDoc);
	if ($rate <= 0) {
		$rate = getRateFromDoc($quantityDoc);
	}

	if (defined $quantityDoc 
		&& defined $quantityDoc->{'daily_quantity'} 
		&& $quantityDoc->{'daily_quantity'} > 0
	) {
		$cost =  $quantityDoc->{'daily_quantity'} * $rate;
	}
	return $cost;
}

sub getDailyBalance {
	my ($rateAmount, $rateQuantity, $dailyQuantity, $yesterdayBalance) = @_;
	return $yesterdayBalance - getDailyCost($rateAmount, $rateQuantity, $dailyQuantity);
}

sub getDailyBalanceFromDoc {
	my ($yesterdayBalance, $quantityDoc, $rateDoc) = @_;
	return $yesterdayBalance - getDailyCostFromDoc($quantityDoc,$rateDoc);
}

sub balanceIsSufficient {
	# Check for sufficient fund
	my ($rateAmount, $rateQuantity, $dailyQuantity, $yesterdayBalance) = @_;
	return (
		getDailyBalance($rateAmount, $rateQuantity, $dailyQuantity, $yesterdayBalance)
		>= getDailyCost($rateAmount, $rateQuantity, $dailyQuantity)
	);
}

sub balanceIsSufficientFromDoc {
	# Check for sufficient fund
	my ($yesterdayBalance, $quantityDoc, $rateDoc) = @_;
	return (
		getDailyBalanceFromDoc($yesterdayBalance, $quantityDoc, $rateDoc)
		>= getDailyCostFromDoc($quantityDoc, $rateDoc)
	);
}

sub paymentDistributionIsComplete {
	my ($paymentDocument) = @_;
	if ( defined $paymentDocument->{'distribution_complete'}
		&& $paymentDocument->{'distribution_complete'} eq 'True' 
	) {
		return 1;
	} else {
		return 0;
	}
}

sub applyPaymentToNewRecord {
	my ($newDistributionRecord, $paymentDocument) = @_;
	$newDistributionRecord->{'payment_record'} =  $paymentDocument->{'_id'};
	$newDistributionRecord->{'distribution_time'} = $paymentDocument->{'distribution_time'};
	$newDistributionRecord->{'daily_quantity'} = $paymentDocument->{'daily_quantity'};
	$newDistributionRecord->{'daily_quantity_unit'} = $paymentDocument->{'daily_quantity_unit'};
	$newDistributionRecord->{'delivery_location'} = $paymentDocument->{'delivery_location'};
	$newDistributionRecord->{'instructions'} = $paymentDocument->{'instructions'};
	return $newDistributionRecord;
}

sub applyItemPriceToNewRecord {
	my ($newDistributionRecord, $paymentDocument, $rateSubRecord, $paymentBalance) = @_;
	$newDistributionRecord->{'rate_for'} = $rateSubRecord->{'for'};
	$newDistributionRecord->{'rate_type'} = $rateSubRecord->{'type'};
	$newDistributionRecord->{'rate_amount'} = $rateSubRecord->{'amount'};
	$newDistributionRecord->{'rate_amount_currency'} = $rateSubRecord->{'currency'};
	$newDistributionRecord->{'rate_quantity'} = $rateSubRecord->{'per'};
	$newDistributionRecord->{'rate_quantity_unit'} = $rateSubRecord->{'per_unit'};
	# Update balance
	$newDistributionRecord->{'payment_balance'} = getDailyBalanceFromDoc ($paymentBalance, $paymentDocument, $rateSubRecord);
	return $newDistributionRecord;
}

sub applyExceptionToNewRecord {
	my ($newDistributionRecord, $exceptionDocument, $paymentBalance) = @_;

	my @toChangeFields = (
		'rate_amount',
		'rate_amount_currency',
		'rate_quantity',
		'rate_quantity_unit',
		'delivery_location',
		'daily_quantity',
		'daily_quantity_unit',
		'instructions',
	);
	foreach my $fieldToChange (@toChangeFields) {
		if ($exceptionDocument->{$fieldToChange} ne '') {
			$newDistributionRecord->{$fieldToChange} = $exceptionDocument->{$fieldToChange};
		}
	}

	# can not be compared as string
	if (defined $exceptionDocument->{'new_distribution_time'}
		&& $exceptionDocument->{'new_distribution_time'} eq 'True'
	) {
		$newDistributionRecord->{'distribution_time'} = $exceptionDocument->{'distribution_time'};
	}

	# Update balance
	$newDistributionRecord->{'payment_balance'} = getDailyBalanceFromDoc ($paymentBalance, $exceptionDocument);
	
	return $newDistributionRecord;
}

sub createNewRecord {
	($paymentDocument, $rateSubRecord, $exceptionDocument, $paymentBalance) = @_;

	my $newDistributionRecord->{'instructions'} = '';

	$newDistributionRecord = applyPaymentToNewRecord ($newDistributionRecord, $paymentDocument);
	$newDistributionRecord = applyItemPriceToNewRecord ($newDistributionRecord, $paymentDocument, $rateSubRecord, $paymentBalance);
	$newDistributionRecord = applyExceptionToNewRecord ($newDistributionRecord, $exceptionDocument, $paymentBalance);
}


# TODO: get the timezone of customer
my $tz = DateTime::TimeZone->new(name => "Asia/Kolkata");

sub getDt {
	my ($dt, $msg) = @_;
	my $ldt = DateTime->new(
		year	=> $dt->{'local_c'}->{'year'},
		month	=> $dt->{'local_c'}->{'month'},
		day	=> $dt->{'local_c'}->{'day'},
		hour	=> $dt->{'local_c'}->{'hour'},
		minute	=> $dt->{'local_c'}->{'minute'},
		second	=> $dt->{'local_c'}->{'second'},
		nanosecond => 0,
		time_zone => $dt->{'tz'}->{'name'},
	);
	# convert to local time zone
	$ldt->add(seconds => $tz->offset_for_datetime($ldt));
	$debug && print "\n\n--------------- $msg " . $ldt->ymd . ' ' . $ldt->hms;
	return $ldt;
}


# $debug && print "\n\n============================================================================";

my $connection = MongoDB::Connection->new ();
my $db = $connection->get_database( 'db1' );

my $paymentCollection = $db->get_collection( 'item_daily_distribution_payment' );
my $exceptionCollection = $db->get_collection( 'item_daily_distribution_exception' );
my $recordCollection = $db->get_collection( 'item_daily_distribution_record' );
my $itemCollection = $db->get_collection( 'item' );
my $personCollection = $db->get_collection( 'person' );

# Read all payment records
my $paymentDocuments = $paymentCollection->find();

# Current epoch time
my $currentDateTime = DateTime->now;
my $oneDay = 60 * 60 * 24; # seconds in a day

while (my $paymentDocument = $paymentDocuments->next) {
	# iterate over each document

	$debugRecord && print "\n\n--------------- Payment Record: " . Dumper($paymentDocument);

	# Find if this payment distribution is complete. If complete then go for
	# next advance payment
	if (paymentDistributionIsComplete($paymentDocument)) { 
		next;  # Read next payment record
	}

	# Calculate disrtibution start date time
	my $startDateTime = getDt($paymentDocument->{'start_date'}, 'distribution start date');
	my $distributeDateTime = getDt($paymentDocument->{'distribution_time'}, 'distribution start time');

	# my $cmp = DateTime->compare($dt1, $dt2);
	# $cmp is -1, 0 or 1, depending on whether $dt1 is less than, equal to, or more than $dt2.
	$debug && print "\n\n--------------- start and current compare: " . DateTime->compare($startDateTime, $currentDateTime);

	if (DateTime->compare($startDateTime, $currentDateTime) > 0) {
		# time has not come to start the distribution
		# we will create and recreate the records when distribution will start
		next; # Read next payment record
	}

	# daily distribution date counter
	my $dailyDistributionEpoch = $startDateTime->epoch();

	# Payment balance
	my $paymentBalance = $paymentDocument->{'paid_amount'};
	$debug && print "\n\n--------------- Paid Amount: " . $paymentBalance;

	# Now find out this payment is for which item
	$debug && print "\n\n--------------- ItemId: " . $paymentDocument->{'item'};	
	my $itemRecord = $itemCollection->find_one({"_id" => $paymentDocument->{'item'}});
	$debugRecord && print "\n\n--------------- Item: " . Dumper($itemRecord);

	# get rate sub record from item document
	my $rateSubRecord = {};
	for my $itemPriceRecord (@{$itemRecord->{'price'}}) {
		# TODO: need logic to choose which price record to use
		$rateSubRecord = $itemPriceRecord;
		$debug && print "\n\n--------------- itemPriceRecord : " . Dumper($itemPriceRecord);
	}

	if (!balanceIsSufficientFromDoc( $paymentBalance, $paymentDocument, $rateSubRecord)) {
		$debug && print "Insufficient funds";
		next; # Go to next payment record
	}

	my $personRecord = $personCollection->find_one({"_id" => $paymentDocument->{'paid_by'}});
	$debugRecord && print "\n\n--------------- Person: " . Dumper($personRecord);

	# print header
	$debug && print "\n" . $itemRecord->{'title'} . " Distribution Report";
	$debug && print "\n" . $itemRecord->{'summary'};
	$debug && print "\nDeliver to " . $personRecord->{'name'}[0]->{'first'}
		. ' ' . $personRecord->{'name'}[0]->{'middle'}
		. ' ' . $personRecord->{'name'}[0]->{'last'} 
		. " at " . $paymentDocument->{'delivery_location'};
	

	# main loop to create each day records
	while ($dailyDistributionEpoch < $currentDateTime->epoch())	{

		if (!balanceIsSufficientFromDoc( $paymentBalance, $paymentDocument, $rateSubRecord)) {
			$debug && print "Insufficient funds";
			last; # Do not create daily records as no funds
		}

		my $dailyDistributionDateTime = DateTime->from_epoch(
			epoch	=> $dailyDistributionEpoch
		);
	
		# Read existing records
		my $distributionRecordsDocument = $recordCollection->find_one({ 
			"payment_record" =>  $paymentDocument->{'_id'},
			"date" => $dailyDistributionDateTime
		});
		$debugRecord && print "\n\n--------------- Distribution Record: " . Dumper($distributionRecordsDocument);

		my $newDistributionRecord = createNewRecord ($paymentDocument, $rateSubRecord, $exceptionDocument, $paymentBalance);


		my $insertOrUpdate = 'insert';
		if (defined $distributionRecordsDocument) {
			$insertOrUpdate = 'update';
			# the distribution record exists
			$newDistributionRecord = $distributionRecordsDocument;
		} else {
			# the distribution record DOES NOT exists
			$newDistributionRecord->{'payment_record'} =  $paymentDocument->{'_id'};
			$newDistributionRecord->{'date'} = $dailyDistributionDateTime;
		} # if (defined $distributionRecordsDocument) 

		# updated the old or new record with payment record definations
		$newDistributionRecord->{'distribution_time'} = $paymentDocument->{'distribution_time'};

		$newDistributionRecord->{'rate_for'} = $rateSubRecord->{'for'};
		$newDistributionRecord->{'rate_type'} = $rateSubRecord->{'type'};
		$newDistributionRecord->{'rate_amount'} = $rateSubRecord->{'amount'};
		$newDistributionRecord->{'rate_amount_currency'} = $rateSubRecord->{'currency'};
		$newDistributionRecord->{'rate_quantity'} = $rateSubRecord->{'per'};
		$newDistributionRecord->{'rate_quantity_unit'} = $rateSubRecord->{'per_unit'};
		$newDistributionRecord->{'payment_balance'} = $paymentBalance;

		$newDistributionRecord->{'daily_quantity'} = $paymentDocument->{'daily_quantity'};
		$newDistributionRecord->{'daily_quantity_unit'} = $paymentDocument->{'daily_quantity_unit'};

		$newDistributionRecord->{'delivery_location'} = $paymentDocument->{'delivery_location'};
		$newDistributionRecord->{'instructions'} .= $paymentDocument->{'instructions'};


		if ( defined $paymentDocument->{'recreate_daily_records'}
			&& $paymentDocument->{'recreate_daily_records'} eq 'False' 
			&& (!DateTime->compare($dailyDistributionDateTime, $currentDateTime))
		) {
			# as recreate is false then only add today's record
			# my $cmp = DateTime->compare($dt1, $dt2);
			# $cmp is -1, 0 or 1, depending on whether $dt1 is less than, equal to, or more than $dt2.
			if (defined $distributionRecordsDocument) {
				# calculate using the stored record
				$paymentBalance = getDailyBalanceFromDoc ($paymentBalance, $distributionRecordsDocument);
			} else {
				# calculate using the current values
				$paymentBalance = getDailyBalanceFromDoc ($paymentBalance, $paymentDocument, $rateSubRecord);
			}
			$dailyDistributionEpoch += $oneDay;
			next; # Go to next day
		}

		my $anyExceptionForToday = 0;

		# findout the exceptions exists for this payment
		my $exceptionDocuments = $exceptionCollection->find({"payment_record" => $paymentDocument->{'_id'}});
		$debugRecord && print "\n\n--------------- Exceptions: " . Dumper($exceptionDocuments);

		while (my $exceptionDocument = $exceptionDocuments->next) {
			my $exceptionStartDateTime = getDt($exceptionDocument->{'start_date'}, 'exception start date');
			my $exceptionEndDateTime = getDt($exceptionDocument->{'end_date'}, 'exception end date');

			# check if this exception is valid
			if ($dailyDistributionEpoch >= $exceptionStartDateTime->epoch() 
				&& $dailyDistributionEpoch <= $exceptionEndDateTime->epoch()) {
				$debug && print "\n\n--------------- found exception";
				$anyExceptionForToday = 1;

				# apply the exceptions
				$debug && print "\n\n--------------- Single Exception: " . Dumper($exceptionDocument);

				my @toChangeFields = (
					'rate_amount',
					'rate_amount_currency',
					'rate_quantity',
					'rate_quantity_unit',
					'delivery_location',
					'daily_quantity',
					'daily_quantity_unit',
					'instructions',
				);
				foreach my $fieldToChange (@toChangeFields) {
					if ($exceptionDocument->{$fieldToChange} ne '') {
						$newDistributionRecord->{$fieldToChange} = $exceptionDocument->{$fieldToChange};
					}
				}

				# can not be compared as string
				if (defined $exceptionDocument->{'new_distribution_time'}
					&& $exceptionDocument->{'new_distribution_time'} eq 'True'
				) {
					$newDistributionRecord->{'distribution_time'} = $exceptionDocument->{'distribution_time'};
				}

				# Update balance
				$paymentBalance = getDailyBalanceFromDoc ($paymentBalance, $exceptionDocument, $rateSubRecord);

				next; # Go to next exception record
			}
			
		} # while (my $exceptionDocument = $exceptionDocuments->next) 

		
		# distribute
		if (!$anyExceptionForToday) {
			# Generally new payment is calculated by the exception, if there is no then calculate here
			$paymentBalance = getDailyBalanceFromDoc ($paymentBalance, $newDistributionRecord, $rateSubRecord);
		}
		$newDistributionRecord->{'payment_balance'} = $paymentBalance;

		$debug && print "\n\n--------------- newDistributionRecord: " . Dumper($newDistributionRecord);

		if (defined $paymentDocument->{'recreate_daily_records'} 
			&& $paymentDocument->{'recreate_daily_records'} eq 'True'
		) {
			# update the paymentRecord with recreate_daily_records as false
			$paymentDocument->{'recreate_daily_records'}  = 'False';
			$paymentCollection->save($paymentDocument);
		}

		print "\n" . $insertOrUpdate 
			. '|' . $newDistributionRecord->{'date'}->ymd
			. '|' . $newDistributionRecord->{'payment_balance'}
		;

		# Save the record
		$recordCollection->save($newDistributionRecord);
		$debug && print "\n\n--------------- newDistributionRecord: " . Dumper($newDistributionRecord);

		$dailyDistributionEpoch += $oneDay;
	} # while ($dailyDistributionEpoch < $currentDateTime->epoch())

} #  while (my $paymentDocument = $paymentDocuments->next) 

$debug && print "\n";


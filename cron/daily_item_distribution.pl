#!/bin/perl -w

use strict;
use warnings;
use Data::Dumper; 
use MongoDB;
use MongoDB::OID;

my $debug = 0;
my $debugRecord = 0;


# my $conection = MongoDB->connect('mongodb://localhost');
my $connection = MongoDB::Connection->new ();

my $db = $connection->get_database( 'db1' );

my $paymentCollection = 	$db->get_collection( 'item_daily_distribution_payment' );
my $exceptionCollection = 	$db->get_collection( 'item_daily_distribution_exception' );
my $recordCollection = 		$db->get_collection( 'item_daily_distribution_record' );
my $itemCollection =		$db->get_collection( 'item' );
my $personCollection = 		$db->get_collection( 'person' );

sub getRate {
	my ($rateAmount, $rateQuantity) = @_;
	my $perUnitRate = 0;
	if ($rateQuantity > 0) {
		$perUnitRate = $rateAmount / $rateQuantity;
	}
	return $perUnitRate;
}

sub getRateFromNewRecordDoc {
	my ($doc) = @_;
	my $perUnitRate = 0;
	if (defined $doc 
		&& defined $doc->{'ipsr_quantity'} 
		&& $doc->{'ipsr_quantity'} gt 0
		&& defined $doc->{'ipsr_amount'} 
		&& $doc->{'ipsr_amount'} gt 0
	) {
		$perUnitRate = $doc->{'ipsr_amount'} / $doc->{'ipsr_quantity'};
		$debug && print "\n\n perUnitRate from new record = " . $perUnitRate;
	} else {
		# this function is used when we calculate rate from the price field of item table
		if (defined $doc 
			&& defined $doc->{'per'} && $doc->{'per'} > 0
			&& defined $doc->{'amount'} && $doc->{'amount'} > 0
		) {
			$perUnitRate = $doc->{'amount'} / $doc->{'per'};
		}
		$debug && print "\n\n perUnitRate from item price sub record = " . $perUnitRate;
	}
	return $perUnitRate;
}

sub getDailyCost {
	# daily cost calculation
	my ($rateAmount, $rateQuantity, $dailyQuantity) = @_;
	return $dailyQuantity * getRate($rateAmount, $rateQuantity);
}

sub getDailyCostFromNewRecordDoc {
	# daily cost calculation
	# item price subRecord does not have daily quantity
	my ($newDistributionRecord, $rateDoc) = @_;
	my $cost = 0;

	my $rate = 0;
	$rate = getRateFromNewRecordDoc($newDistributionRecord);
	if ($rate <= 0) {
		$rate = getRateFromNewRecordDoc($rateDoc);
	}

	my $quantity = 0;
	if (defined $newDistributionRecord && defined $newDistributionRecord->{'delivery_quantity'}) {
		$quantity =  $newDistributionRecord->{'delivery_quantity'};
	} elsif (defined $rateDoc && defined $rateDoc->{'delivery_quantity'}) {
		$quantity =  $rateDoc->{'delivery_quantity'};
	}

	if (defined $rate && defined $quantity && $rate > 0 && $quantity > 0) {
		$cost =  $quantity * $rate;
	}

	# Add per visit distribution charge
	# ipsr_daily_distribution_charge_per_unit
	if (defined $newDistributionRecord && defined $newDistributionRecord->{'ipsr_daily_distribution_charge_per_visit'}) {
		$cost = $cost + $newDistributionRecord->{'ipsr_daily_distribution_charge_per_visit'};
	} elsif (defined $rateDoc && defined $rateDoc->{'ipsr_daily_distribution_charge_per_visit'}) {
		$cost = $cost + $rateDoc->{'ipsr_daily_distribution_charge_per_visit'};
	}

	# Add per unit distribution charge
	if (defined $newDistributionRecord && defined $newDistributionRecord->{'ipsr_daily_distribution_charge_per_unit'}) {
		$cost =  $cost + $quantity * $newDistributionRecord->{'ipsr_daily_distribution_charge_per_unit'};
	} elsif (defined $rateDoc && defined $rateDoc->{'ipsr_daily_distribution_charge_per_unit'}) {
		$cost =  $cost + $quantity * $rateDoc->{'ipsr_daily_distribution_charge_per_unit'};
	}

	return $cost;
}

sub getDailyBalance {
	my ($rateAmount, $rateQuantity, $dailyQuantity, $yesterdayBalance) = @_;
	return $yesterdayBalance - getDailyCost($rateAmount, $rateQuantity, $dailyQuantity);
}

sub getDailyBalanceFromDoc {
	my ($yesterdayBalance, $newDistributionRecord, $rateDoc) = @_;
	return $yesterdayBalance - getDailyCostFromNewRecordDoc($newDistributionRecord,$rateDoc);
}

sub balanceIsNotSufficientFromDoc {
	# Check for sufficient fund
	my ($yesterdayBalance, $newDistributionRecord, $rateDoc) = @_;
	# $debug && print "Insufficient funds";
	return (
		getDailyBalanceFromDoc($yesterdayBalance, $newDistributionRecord, $rateDoc)
		< getDailyCostFromNewRecordDoc($newDistributionRecord, $rateDoc)
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
	$newDistributionRecord->{'paid_as_advance'} =  $paymentDocument->{'paid_as_advance'};
	$newDistributionRecord->{'other_amount'} =  0;
	$newDistributionRecord->{'other_amount_explanation'} =  '';
	if (defined $paymentDocument->{'other_amount'} 
		&& ref($paymentDocument->{'other_amount'}) eq 'ARRAY'
		&& @{$paymentDocument->{'other_amount'}}
	) {
		foreach my $otherAmountRecord ( @{$paymentDocument->{'other_amount'}} ) {
			if (defined $otherAmountRecord->{'received'} && $otherAmountRecord->{'received'} ne '') {
				$newDistributionRecord->{'other_amount'} +=  $otherAmountRecord->{'received'};
			}
			if (defined $otherAmountRecord->{'paid'} && $otherAmountRecord->{'paid'} ne '') {
				$newDistributionRecord->{'other_amount'} -=  $otherAmountRecord->{'paid'};
			}
			if (defined $otherAmountRecord->{'to_be_received'} && $otherAmountRecord->{'to_be_received'} ne '') {
				$newDistributionRecord->{'other_amount'} -=  $otherAmountRecord->{'to_be_received'};
			}
			if (defined $otherAmountRecord->{'to_be_paid'} && $otherAmountRecord->{'to_be_paid'} ne '') {
				$newDistributionRecord->{'other_amount'} -=  $otherAmountRecord->{'to_be_paid'};
			}
			if (defined $otherAmountRecord->{'explanation'} && $otherAmountRecord->{'explanation'} ne '') {
				$newDistributionRecord->{'other_amount_explanation'} .=  $otherAmountRecord->{'explanation'} . ', ';
			}
		}
	}
	if ($newDistributionRecord->{'paid_as_advance'} eq 'False') {
		$newDistributionRecord->{'other_amount'} -=  $paymentDocument->{'paid_amount'};
		$newDistributionRecord->{'other_amount_explanation'} .=  'Advance payment of current month, ';
	#} else {
		# $newDistributionRecord->{'other_amount'} +=  $paymentDocument->{'paid_amount'};
	}
	# Remove last comma and space
	$newDistributionRecord->{'other_amount_explanation'} =  substr($newDistributionRecord->{'other_amount_explanation'}, 0, -2);
	
	$newDistributionRecord->{'instructions'} = '';
	return $newDistributionRecord;
}

sub applyDeliveryToNewRecord {
	my ($newDistributionRecord, $deliveryRecord) = @_;

	$newDistributionRecord->{'delivery_distribution_time'} = (defined $deliveryRecord->{'distribution_time'}) 
		? $deliveryRecord->{'distribution_time'} : '7:00 am';
	$newDistributionRecord->{'delivery_is_urgent'} = (defined $deliveryRecord->{'is_urgent'}) 
		? $deliveryRecord->{'is_urgent'} : 'False';
	$newDistributionRecord->{'delivery_do_ring_bell'} = (defined $deliveryRecord->{'do_ring_bell'}) 
		? $deliveryRecord->{'do_ring_bell'} : 'True';

	$newDistributionRecord->{'delivery_quantity'} = (defined $deliveryRecord->{'quantity'})
		? $deliveryRecord->{'quantity'} : 0;
	$newDistributionRecord->{'delivery_quantity_unit'} = (defined $deliveryRecord->{'quantity_unit'})
		? $deliveryRecord->{'quantity_unit'} : 'Unknown';

	$newDistributionRecord->{'delivery_location'} = (defined $deliveryRecord->{'location'}) 
		? $deliveryRecord->{'location'} : 'Unknown';
	$newDistributionRecord->{'delivery_location_code'} = (defined $deliveryRecord->{'location_code'})
		? $deliveryRecord->{'location_code'} : 'Unknown';

	return $newDistributionRecord;
}

sub applyItemPriceToNewRecord {
	my ($newDistributionRecord, $itemRateSubRecord) = @_;
	# ipsr = Item Price Sub Record

	$newDistributionRecord->{'ipsr_for'} = (defined $itemRateSubRecord->{'for'}) 
		? $itemRateSubRecord->{'for'} : 'Make and Sale';

	$newDistributionRecord->{'ipsr_type'} = (defined $itemRateSubRecord->{'type'}) 
		? $itemRateSubRecord->{'type'} : 'Amount';

	$newDistributionRecord->{'ipsr_amount'} = (defined $itemRateSubRecord->{'amount'})
		? $itemRateSubRecord->{'amount'} : 0;

	$newDistributionRecord->{'ipsr_amount_currency'} = (defined $itemRateSubRecord->{'currency'})
		? $itemRateSubRecord->{'currency'} : 'INR';

	$newDistributionRecord->{'ipsr_quantity'} = (defined $itemRateSubRecord->{'per'})
		? $itemRateSubRecord->{'per'} : 1;

	$newDistributionRecord->{'ipsr_quantity_unit'} = (defined $itemRateSubRecord->{'per_unit'})
		? $itemRateSubRecord->{'per_unit'} : 'Unknown';

	$newDistributionRecord->{'ipsr_daily_distribution_charge_per_visit'} = 
		(defined $itemRateSubRecord->{'daily_distribution_charge_per_visit'})
		? $itemRateSubRecord->{'daily_distribution_charge_per_visit'} : '';

	$newDistributionRecord->{'ipsr_daily_distribution_charge_per_unit'} = 
		(defined $itemRateSubRecord->{'daily_distribution_charge_per_unit'})
		? $itemRateSubRecord->{'daily_distribution_charge_per_unit'} : '';

	return $newDistributionRecord;
}

sub applyExceptionToNewRecord {
	my ($newDistributionRecord, $paymentId,  $deliveryStartDateEpoch) = @_;

	my $anyExceptionForToday = 0;

	# findout the exceptions exists for this payment
	my $exceptionDocuments = $exceptionCollection->find({"payment_record" => $paymentId});
	$debugRecord && print "\n\n--------------- Exceptions: " . Dumper($exceptionDocuments);

	while (my $exceptionDocument = $exceptionDocuments->next) {
		my $exceptionStartDateTime = getDt($exceptionDocument->{'start_date'}, 'exception start date');
		my $exceptionEndDateTime = getDt($exceptionDocument->{'end_date'}, 'exception end date');

		# check if this exception is valid
		if ($deliveryStartDateEpoch >= $exceptionStartDateTime->epoch() 
			&& $deliveryStartDateEpoch <= $exceptionEndDateTime->epoch()) {
			$debug && print "\n\n--------------- found exception";
			$anyExceptionForToday = 1;

			# apply the exceptions
			$debug && print "\n\n--------------- Single Exception: " . Dumper($exceptionDocument);

			if (defined $exceptionDocument->{'new_distribution_time'}
				&& defined $exceptionDocument->{'distribution_time'}
				&& $exceptionDocument->{'new_distribution_time'} eq 'True'
			) {
				$newDistributionRecord->{'delivery_distribution_time'} = $exceptionDocument->{'distribution_time'};
			}

			if (defined $exceptionDocument->{'rate_amount'} && $exceptionDocument->{'rate_amount'} ne '') {
				$newDistributionRecord->{'ipsr_amount'} = $exceptionDocument->{'rate_amount'};
			}

			if (defined $exceptionDocument->{'rate_amount_currency'} && $exceptionDocument->{'rate_amount_currency'} ne '') {
				$newDistributionRecord->{'ipsr_amount_currency'} = $exceptionDocument->{'rate_amount_currency'};
			}

			if (defined $exceptionDocument->{'rate_quantity'} && $exceptionDocument->{'rate_quantity'} ne '') {
				$newDistributionRecord->{'ipsr_quantity'} = $exceptionDocument->{'rate_quantity'};
			}

			if (defined $exceptionDocument->{'rate_quantity_unit'} && $exceptionDocument->{'rate_quantity_unit'} ne '') {
				$newDistributionRecord->{'ipsr_quantity_unit'} = $exceptionDocument->{'rate_quantity_unit'};
			}

			if (defined $exceptionDocument->{'delivery_location'} && $exceptionDocument->{'delivery_location'} ne '') {
				$newDistributionRecord->{'delivery_location'} = $exceptionDocument->{'delivery_location'};
			}

			if (defined $exceptionDocument->{'delivery_location_code'} && $exceptionDocument->{'delivery_location_code'} ne '') {
				$newDistributionRecord->{'delivery_location_code'} = $exceptionDocument->{'delivery_location_code'};
			}

			if (defined $exceptionDocument->{'delivery_quantity'} && $exceptionDocument->{'delivery_quantity'} ne '') {
				$newDistributionRecord->{'delivery_quantity'} = $exceptionDocument->{'delivery_quantity'};
			}

			if (defined $exceptionDocument->{'delivery_quantity_unit'} && $exceptionDocument->{'delivery_quantity_unit'} ne '') {
				$newDistributionRecord->{'delivery_quantity_unit'} = $exceptionDocument->{'delivery_quantity_unit'};
			}

			if (defined $exceptionDocument->{'instructions'} && $exceptionDocument->{'instructions'} ne '') {
				$newDistributionRecord->{'instructions'} = $exceptionDocument->{'instructions'};
			}

		}
		
	} # while (my $exceptionDocument = $exceptionDocuments->next) 
	
	return $newDistributionRecord;
}

sub createNewRecord {
	my ($paymentDocument, $deliveryRecord, $itemRateSubRecord, $paymentBalance, $deliveryStartDateEpoch) = @_;

	my $newDistributionRecord->{'instructions'} = '';


	$newDistributionRecord = applyPaymentToNewRecord (
		$newDistributionRecord, 
		$paymentDocument
	);

	$newDistributionRecord = applyDeliveryToNewRecord (
		$newDistributionRecord, 
		$deliveryRecord
	);

	$newDistributionRecord = applyItemPriceToNewRecord (
		$newDistributionRecord, 
		$itemRateSubRecord 
	);

	$newDistributionRecord = applyExceptionToNewRecord (
		$newDistributionRecord, 
		$paymentDocument->{'_id'},  
		$deliveryStartDateEpoch
	);

	$newDistributionRecord->{'date'} = DateTime->from_epoch(
                epoch   => $deliveryStartDateEpoch
	);
	
	$newDistributionRecord->{'payment_balance'} = $paymentBalance - getDailyCostFromNewRecordDoc($newDistributionRecord, $itemRateSubRecord);

	return $newDistributionRecord;
}

sub startDateIsNotArrived {
	my ($startDate, $nowDate) = @_;
	# my $cmp = DateTime->compare($dt1, $dt2);
	# $cmp is -1, 0 or 1, depending on whether $dt1 is less than, equal to, or more than $dt2.
	$debug && print "\n\n--------------- start and current compare: " 
		. DateTime->compare($startDate, $nowDate);
	if (DateTime->compare($startDate, $nowDate) > 0) {
		# time has not come to start the distribution
		# we will create and recreate the records when distribution will start
		$debug && print "\n\n--------------- go to next record";
		return 1; #true
	}
	return 0; #false
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

sub getItemRateRecord {
	my ($itemId) = @_;
	#
	# Now find out this payment is for which item
	$debug && print "\n\n--------------- ItemId: " . $itemId;	
	my $itemRecord = $itemCollection->find_one({"_id" => $itemId});
	$debugRecord && print "\n\n--------------- Item: " . Dumper($itemRecord);

	# get rate sub record from item document
	my $itemRateSubRecord = {};
	for my $itemPriceRecord (@{$itemRecord->{'price'}}) {
		# TODO: need logic to choose which price record to use
		$itemRateSubRecord = $itemPriceRecord;
		$debugRecord && print "\n\n--------------- itemPriceRecord : " . Dumper($itemPriceRecord);
	}

	$debug && print "\n" . $itemRecord->{'title'} . " Distribution Report";
	$debug && print "\n" . $itemRecord->{'summary'};

	return $itemRateSubRecord;
}

sub getCustomerRecord {
	my ($personId) = @_;
	#
	# Read customer record
	my $personRecord = $personCollection->find_one({"_id" => $personId });
	$debugRecord && print "\n\n--------------- Person: " . Dumper($personRecord);

	# print header
	$debug && print "\nDeliver to " . $personRecord->{'name'}[0]->{'first'}
		. ' ' . $personRecord->{'name'}[0]->{'middle'}
		. ' ' . $personRecord->{'name'}[0]->{'last'};

	return $personRecord;
}

sub toRecreateAllRecordsDeleteAllOldRecordsForThisPayment {
	my ($paymentDocument) = @_;

	# if we asked to recreate all the records for this payment then remove all old records
	# This way it will create all the records only once
	if ( defined $paymentDocument->{'recreate_daily_records'}
		&& $paymentDocument->{'recreate_daily_records'} eq 'True' 
	) {
		# To recreate new ercords first remove existing records
		$debug && print "\n\n -------------- deleting all records for payment " . $paymentDocument->{'_id'};
		my $result = $recordCollection->remove({ 
			'payment_record' =>  $paymentDocument->{'_id'}
		});
	}
}

sub getOldDistributionRecord {
	my ($paymentRecordId, $distributionTimeEpoch) = @_;

	# my $distributeDateTime = getDt( $distributionTime, 'distribution time');

	my $dailyDistributionDateTime = DateTime->from_epoch(
		epoch	=> $distributionTimeEpoch
	);

	# Read existing records
	my $distributionRecordsDocument = $recordCollection->find_one({ 
		"payment_record" =>  $paymentRecordId,
		"date" => $dailyDistributionDateTime
	});

	$debug && print "\n\n--------------- does exsisting record defined: ";
	$debug && ((defined $distributionRecordsDocument) ? print "Yes" : print "No");
	$debugRecord && print "\n\n--------------- Distribution Record: " . Dumper($distributionRecordsDocument);

	return $distributionRecordsDocument;
}
#
# Read all payment records
my $paymentDocuments = $paymentCollection->find();

# Current epoch time
my $currentDateTime = DateTime->now;
my $oneDay = 60 * 60 * 24; # seconds in a day

while (my $paymentDocument = $paymentDocuments->next) {
	$debug && print "\n\n============================================================================";

	# iterate over each document
	$debugRecord && print "\n\n--------------- Payment Record: " . Dumper($paymentDocument);

	# Find if this payment distribution is complete. If complete then go for next advance payment
	paymentDistributionIsComplete($paymentDocument) && next; 

	# Calculate disrtibution start date time
	my $startDateTime = getDt($paymentDocument->{'start_date'}, 'distribution start date');

	# If start date is not arrived then go for next payment
	startDateIsNotArrived ($startDateTime, $currentDateTime) && next;
	$debug && print "\n\n--------------- Start date arrived";

	# Payment balance
	my $paymentBalance = $paymentDocument->{'paid_amount'};
	$debug && print "\n\n--------------- Paid Amount: " . $paymentBalance;
	
	my $itemRateSubRecord = getItemRateRecord($paymentDocument->{'item'});

	# Does customer has enough balance to get daily supply
	# We do not know the quantity yet until we iterate over delivery record
	# balanceIsNotSufficientFromDoc( $paymentBalance, $paymentDocument, $itemRateSubRecord) && next;

	my $customerRecord = getCustomerRecord ($paymentDocument->{'paid_by'});
	
	# force recreation of all current records
	# $paymentDocument->{'recreate_daily_records'} = 'True';
	
	toRecreateAllRecordsDeleteAllOldRecordsForThisPayment($paymentDocument);
	
	# daily distribution date counter
	my $deliveryStartDateEpoch = $startDateTime->epoch();
	$debug && print "\n\n--------------- start date epoch: " . $deliveryStartDateEpoch;
	$debug && print "\n\n--------------- current date epoch: " . $currentDateTime->epoch();

	my $newDistributionRecord = {};

	# main loop to create each day records
	while ($deliveryStartDateEpoch <= ($currentDateTime->epoch() + $oneDay))	{
		$debug && print "\n\n--------------- start date" . DateTime->from_epoch( epoch   => $deliveryStartDateEpoch);
		$debug && print "\n\n--------------- current date" . DateTime->from_epoch( epoch   => ($currentDateTime->epoch() + $oneDay));

		$debug && print "\n\n--------------- recreate_daily_records :" . $paymentDocument->{'recreate_daily_records'};

		my $distributionRecordsDocument = 
			getOldDistributionRecord($paymentDocument->{'_id'}, $deliveryStartDateEpoch);


		# if distribution record exists do not create new
		# if we have recreate flag true then we delete all old records earlier
		# in that case it will ceaet new record
		# as we are running this script every 10 minutes so create if not created earlier
		if (defined $distributionRecordsDocument) {
			$deliveryStartDateEpoch += $oneDay;
			next; # next delivery record
		}

		$debug && print "\n\n--------------- this distribution record does not exists";

		if (defined $paymentDocument->{'delivery'}) {
			$debug && print "\n\n--------------- delivery sub record exist";

			foreach my $deliveryRecord ( @{$paymentDocument->{'delivery'}} ) {

				if ( defined $paymentDocument->{'recreate_daily_records'}
					&& $paymentDocument->{'recreate_daily_records'} eq 'False' 
					&& $deliveryStartDateEpoch > ($currentDateTime->epoch() + $oneDay)
				) {
					# recreate == true
					# 	create new record
					# recreate == false
					# 	create only today's record
					# my $cmp = DateTime->compare($dt1, $dt2);
					# $cmp is -1, 0 or 1, depending on whether $dt1 is less than, equal to, or more than $dt2.
					$paymentBalance = $distributionRecordsDocument->{'payment_balance'};
					$deliveryStartDateEpoch += $oneDay;
					$debug && print "\n\n--------------- go to next record (only today record will be created)";
					next; # Go to next day
				}
 				
				# Create the new distribution record
				$newDistributionRecord = createNewRecord (
					$paymentDocument, 
					$deliveryRecord, 
					$itemRateSubRecord, 
					$paymentBalance,
					$deliveryStartDateEpoch
				);
				$debug && print "\n\n--------------- newDistributionRecord created "; 
				$debugRecord && print "\n\n--------------- New Distribution Record: " . Dumper($newDistributionRecord);
				$debug && print "\n\n--------------- New Distribution Record: " . Dumper($newDistributionRecord);
 				
				$debug && print "\n" . $newDistributionRecord->{'delivery_location'} 
					. ' | payment_balance ' . $newDistributionRecord->{'payment_balance'}
					. ' | delivery_quantity ' . $newDistributionRecord->{'delivery_quantity'}
					. ' | ' . $newDistributionRecord->{'date'}
				;
 				
 				# Do not create daily records as no funds
				if ($newDistributionRecord->{'payment_balance'} <= 0) {
					next;
				}


				# Save the record
				$recordCollection->save($newDistributionRecord);
				$debug && print "\n\n--------------- newDistributionRecord saved "; 
				$debugRecord && print "\n\n--------------- newDistributionRecord: " . Dumper($newDistributionRecord);
				
				# Update the payment balance
				$paymentBalance = $newDistributionRecord->{'payment_balance'};

			} # foreach ( @{$paymentDocument->{'delivery'}}) 

		} # if (defined $paymentDocument->{'delivery'}) {

		$deliveryStartDateEpoch += $oneDay;

	} # while ($deliveryStartDateEpoch < $currentDateTime->epoch())

	$debug && print "\n";
	if (defined $paymentDocument->{'recreate_daily_records'} 
		&& $paymentDocument->{'recreate_daily_records'} eq 'True'
	) {
		# update the paymentRecord with recreate_daily_records as false
		$paymentDocument->{'recreate_daily_records'}  = 'False';
		$paymentCollection->save($paymentDocument);
	}

} #  while (my $paymentDocument = $paymentDocuments->next) 

$debug && print "\n";


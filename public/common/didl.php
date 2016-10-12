<?php

/**
 * session_start() creates a session or resumes the current one based on a session
 * identifier passed via a GET or POST request, or passed via a cookie.
 */
session_start ();

/**
 * A constant to hold the absolute path of ajpm lib folder on server
 *
 * @constant string DIR
 */
define ( 'SERVER_SIDE_PUBLIC_DIR', __DIR__ );

define ( 'SERVER_SIDE_LIB_DIR', SERVER_SIDE_PUBLIC_DIR
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'backend'
 	. DIRECTORY_SEPARATOR . 'php'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'frontend'
	. DIRECTORY_SEPARATOR . 'admin'
	. DIRECTORY_SEPARATOR . 'php'
);

/**
 * Include the common files
 */
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'debug.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';

// Prevent caching.
header ( 'Cache-Control: no-cache, must-revalidate' );
$offset = 60; # 60 seconds
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ( 'Expires: ' . $expire );

// The JSON standard MIME header.
header ( 'Content-type: text/html' );

$locale='en-US'; //browser or user locale
$errorMessage = '';
$distributionTimeGroups = array();
$total = array();
$pendingAmountSms = ''; 

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

debugPrintArray($urlArgsArray,'urlArgsArray');
debugPrintArray($_SESSION,'_SESSION');

if (!isset($urlArgsArray ['i']) || $urlArgsArray ['i'] == '') {
	$errorMessage .= "Item code is missing in the URL request";
}

if (!isValidMongoObjectID($urlArgsArray ['i'])) {
        $errorMessage .= " | Wrong code";
}

$itemRecord = $_SESSION ['mongo_database']->item->findOne (array(
	'_id' => new MongoId((string)(trim($urlArgsArray ['i'])))
) );
$itemRecordId = $itemRecord ['_id'] instanceof MongoId ? $itemRecord ['_id'] : new MongoId($itemRecord ['_id']);

if (!isset($urlArgsArray ['p']) || $urlArgsArray ['p'] == '') {
	$errorMessage .= "Access code is missing";
} else {
	if ($urlArgsArray ['p'] != md5($itemRecord['distribution_report_password'])) {
		$errorMessage .= "Access code is wrong";
	}
}

# Report header
echo $_SESSION['url_domain_org']['name'] . '<br />' . $_SESSION['url_domain_org']['statement'];
echo '<hr /><b>Distribution List for Manager</b><br />';
echo "Item: " . $itemRecord['title'];

# Show error and exit
if ($errorMessage != '') { echo $errorMessage; exit; }
 


$todayStart = New Mongodate(strtotime('today'));
$tomorrowEnd   = New Mongodate(strtotime("tomorrow + 1day"));
debugPrintArray($todayStart->sec,'todayStart');
debugPrintArray($tomorrowEnd->sec,'tomorrowEnd');
$currencySymbol = '';

?>

<ul>
<li>All tables are ordered by location.</li>
<li>Charge is home delivery expense.</li>
<li>Click on quantity to create the exception record</li>
<li>Click on balance to update the payment record</li>
<li>Click on location to see detail bill as customer see it</li>
</ul>

<hr /><b>Table of all dsitributions.</b>

<?php

function getCurrency($amount, $recordDoc) {
	# $fmt = new NumberFormatter( $locale."@currency=".$recordDoc['ipsr_amount_currency'], NumberFormatter::CURRENCY );
	# $currencySymbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
	$currencySymbol = $recordDoc['ipsr_amount_currency'];
	if ($recordDoc['ipsr_amount_currency'] == 'INR') {
		$currencySymbol = 'Rs';
	}
	return $currencySymbol . number_format($amount, 2,'.',',');
}

function getDateCell($recordDoc) {
	return getAsCell(date('Y-M-d D', $recordDoc['date']->sec));
}

function getTimeCell($recordDoc) {
	return getAsCell(date('h:i A', $recordDoc['delivery_distribution_time']->sec));
}

function getUrgentCell($recordDoc) {
	$a = ($recordDoc['delivery_is_urgent'] == 'True')?'Yes':'';
	return getAsCell($a);
}

function getRingBellCell($recordDoc) {
	$a = ($recordDoc['delivery_do_ring_bell'] == 'True')?'':'No';
	return getAsCell($a);
}

function getQuantityCell($recordDoc, $paymentDoc, $sms = true) {
	$q = $recordDoc['delivery_quantity_unit'];
	if ($sms) $q = substr($recordDoc['delivery_quantity_unit'],0,1);

	if (empty($paymentDoc)) {
		$paymentDoc['_id'] = '';
		$paymentDoc['delivery'][0]['quantity_unit'] = '';
		$paymentDoc['delivery'][0]['quantity_unit'] = '';
	}
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] . '/item_distribution_exception/create/All'
			. '?payment_record=' . (string)($paymentDoc['_id'])
			. '&rate_quantity_unit=' . $paymentDoc['delivery'][0]['quantity_unit']
			. '&delivery_quantity_unit=' . $paymentDoc['delivery'][0]['quantity_unit']
			,
		        getAlertText(
				number_format($recordDoc['delivery_quantity'], 1, '.', '') 
				. ' ' . $q,
				($recordDoc['delivery_quantity'] <= 0) 
			)
		)
	);
}

function getStartDateCell($recordDoc, $paymentDoc) {
	if (!array_key_exists('start_date', $paymentDoc)) {
		return 'Missing';
	}
	return getAsCell(
        	getAlertText(
			date('Y-M-d D', $paymentDoc['start_date']->sec),
			(date('M', $paymentDoc['start_date']->sec) != date('M', $recordDoc['date']->sec))
		)
	);
}

function getLocationCell($recordDoc) {
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] . '/common/idr.php?c=' . $recordDoc['payment_record'],
			$recordDoc['delivery_location_code']
		)
	);
}

function getEmailCell($recordDoc) {
	return getAsCell(
		getLink(
			'mailto:customer_email_address?subject=Link%20to%20HYBR%20Farm%20milk%20bill%20for%20'
			.$recordDoc['delivery_location']
			.'&body='.$recordDoc['delivery_location']
			.',%0APlease%20chek%20following%20link%20for%20bill%20details.%0Ahttp://farm.hybr.in/common/idr.php?c='
			.$recordDoc['payment_record'].'%0AThanks%0A'.$_SESSION['url_domain_org']['name'],
			'Email'
		)
	);
}

function getSmsCell($recordDoc, $paymentDoc) {
	if (!array_key_exists('start_date', $paymentDoc) || $paymentDoc['start_date'] == '') {
		return 'Missing start date';
	}
	return getAsCell(
		getSmsLink(
			'',
			date('Y-M', $paymentDoc['start_date']->sec)
			.'%20HYBR%20Farm%20milk%20bill%20link%0Ahttp://farm.hybr.in/common/idr.php?c='
			.$recordDoc['payment_record'].'%0AThanks%0A'
		)
	);
}

function getCopyCell($recordDoc, $paymentDoc) {
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] 
			. '/item_payment/copy/All?id=' . $paymentDoc['_id'],
			'Copy'
		)
	);
}

function getCostCell($recordDoc) {
	return getAsCell(
		getCurrency(($recordDoc['ipsr_amount']/$recordDoc['ipsr_quantity']) * $recordDoc['delivery_quantity'], $recordDoc),
		'currency'
	);
}

function getChargeCell($recordDoc) {
	return getAsCell(
		getCurrency(
			($recordDoc['ipsr_distribution_charge_per_visit'] 
			+ ($recordDoc['ipsr_distribution_charge_per_unit'] * $recordDoc['delivery_quantity'])),
			$recordDoc
		),
		'currency'
	);
}

function getBalanceCell($recordDoc, $paymentDoc) {
	return getAsCell(
		getUpdateRecordLink(
			'item_payment', 
			$paymentDoc['_id'], 
		        getAlertText(
				getCurrency($recordDoc['payment_balance'] + $recordDoc['other_amount'], $recordDoc),
				(($recordDoc['payment_balance']+$recordDoc['other_amount']) < 0) 
			)
		),
		'currency'
	);
}

function getAdvanceCell($recordDoc) {
	$a = ($recordDoc['paid_as_advance'] == 'True')?'':'No';
	return getAsCell($a);
}

function getInstructionsCell($recordDoc) {
	return getAsCell($recordDoc['instructions']);
}

function getBillVisits($recordDoc, $paymentDoc) {
	if ( array_key_exists('remote_addresses', $paymentDoc) ) {
		return getAsCell((substr_count($paymentDoc['remote_addresses'], ',') + 1));
	} else {
		return getAsCell('0');
	}
}
function getRow ($recordDoc, $paymentDoc) {
	$rStr = '';
	$rStr .= getRowStart();
	$rStr .= getDateCell($recordDoc);
	$rStr .= getTimeCell($recordDoc);
	$rStr .= getUrgentCell($recordDoc);
	$rStr .= getRingBellCell($recordDoc);
	$rStr .= getQuantityCell($recordDoc, $paymentDoc, false);
	$rStr .= getStartDateCell($recordDoc, $paymentDoc);
	$rStr .= getLocationCell($recordDoc);
	$rStr .= getEmailCell($recordDoc);
	$rStr .= getSmsCell($recordDoc, $paymentDoc);
	$rStr .= getCopyCell($recordDoc, $paymentDoc);
	$rStr .= getCostCell($recordDoc, $paymentDoc);
	$rStr .= getChargeCell($recordDoc); 
	$rStr .= getBalanceCell($recordDoc, $paymentDoc);
	$rStr .= getAdvanceCell($recordDoc);
	$rStr .= getInstructionsCell($recordDoc);
	$rStr .= getBillVisits($recordDoc, $paymentDoc);
	$rStr .= getRowEnd();
	return $rStr;
}

function getSmsRow ($recordDoc) {
        $rStr = '';
	$rStr .= getRowStart();
        $rStr .= getQuantityCell($recordDoc, array(), true);
        $rStr .= getLocationCell($recordDoc);
        $rStr .= getUrgentCell($recordDoc);
        $rStr .= getRingBellCell($recordDoc);
        $rStr .= getRowEnd();
        return $rStr;
}

function getTimeGroupDateString($recordDoc) {
	return date('Y-M-d D',$recordDoc['date']->sec) . ' ' 
		. date('h:i A',$recordDoc['delivery_distribution_time']->sec);
}
function updateDistributionTimeGroup ($recordDoc) {
	global $distributionTimeGroups;
	/* grouping of records for SMS tables */
	$dateString = getTimeGroupDateString($recordDoc);
	if (!array_key_exists($dateString, $distributionTimeGroups)) {
		$distributionTimeGroups[$dateString] = array();
	}
	array_push($distributionTimeGroups[$dateString], $recordDoc);
}

function calculateTotals($recordDoc) {
	global $total;

		if (!array_key_exists('effective_balance', $total)) {
			$total['effective_balance'] = 0;
		}
		$total['effective_balance'] += $recordDoc['other_amount'] + $recordDoc['payment_balance'];

		if (!array_key_exists('due', $total)) {
			$total['due'] = 0;
		}
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] < 0) {
			$total['due'] += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}

		if (!array_key_exists('extra', $total)) {
			$total['extra'] = 0;
		}
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] > 0) {
			$total['extra'] += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}

		if (!array_key_exists('delivery_quantity', $total)) {
			$total['delivery_quantity'] = 0;
		}
		if (array_key_exists('delivery_quantity',$recordDoc)){
			$total['delivery_quantity'] += $recordDoc['delivery_quantity'];
		}

		if (!array_key_exists('absent_quantity', $total)) {
			$total['absent_quantity'] = 0;
		}
		if ($recordDoc['delivery_quantity'] == 0 && array_key_exists('orig_delivery_quantity',$recordDoc)){
			$total['absent_quantity'] += $recordDoc['orig_delivery_quantity'];
		}

		if (!array_key_exists('income', $total)) {
			$total['income'] = 0;
		}
		$total['income'] += (($recordDoc['ipsr_amount']/$recordDoc['ipsr_quantity']) * $recordDoc['delivery_quantity']);


		if (!array_key_exists('delivery_quantity_unit', $total)) {
			$total['delivery_quantity_unit'] = $recordDoc['delivery_quantity_unit'];
		}
}

function printTotals() {
	global $total;
	ksort($total);	
	echo '<table border=1><tbody>';
	foreach ( $total as $key => $value ) {
		if ($key != '') {
			echo '<tr><td>' . getTitle($key) . '</td><td>';
			if (gettype($value) == 'double') {
				echo number_format($value, 1, '.', ',') ;
			} else {
				echo $value;
			}
			echo '</td></tr>';
		}
	}
	echo '</tbody></table>';

	/*
	echo '<br />Approximate monthly: ' 
		. $currencySymbol 
		. number_format($total['cost'] * 30, 2, '.', ','); 

	*/
}

function printTodayDistributionRecord($paymentDoc, $completedPayments) {

	global $distributionTimeGroups;
	global $total;
	global $pendingAmountSms;

	# read distribution records for this payment
	if ($completedPayments == 'False') {
		$cond = array(
			'$and' => array(
				array('date' => array('$gte' => $GLOBALS['todayStart'])),
				array('payment_record' => $paymentDoc['_id'])
			)
		);
	} else {
		$cond = array(
			'payment_record' => $paymentDoc['_id']
		);
	}
	$distributionRecordsCursor = $_SESSION ['mongo_database']
		->item_distribution_record->find ($cond);

	# 'item' => $itemRecord['_id']
	/* "date" => array('$gte' => $todayStart, '$lte' => $tomorrowEnd) */
	$distributionRecordsCursor->sort(array('date' => -1));

	debugPrintArray($distributionRecordsCursor,'distributionRecordsCursor');

	# process each distribution record
	foreach ( $distributionRecordsCursor as $recordDoc ) {
		# debugPrintArray($recordDoc,'recordDoc');
		
		updateDistributionTimeGroup ($recordDoc, $distributionTimeGroups);

		/* show only today record */
		if (	date('Y-M-d',$recordDoc['date']->sec) == date('Y-M-d',$GLOBALS['todayStart']->sec)
			&& $completedPayments == 'False'
		) { continue; }

		/* show a row */
		echo getRow($recordDoc, $paymentDoc);

		calculateTotals($recordDoc);

                if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] < 0) {
			$pendingAmountSms .= '%0A' . ($recordDoc['other_amount'] + $recordDoc['payment_balance']) 
				. ' | ' . $recordDoc['delivery_location_code'];
                }

		if ($completedPayments == 'True') { break; }
	} # foreach ( $distributionRecordsCursor as $recordDoc ) 

}

function printTodayPayments($completedPayments = 'True') {
	global $distributionTimeGroups;
	global $total;
	global $pendingAmountSms;

	$distributionTimeGroupsRef = array();
	$total = array();
	$pendingAmountSms = '';

	echo '<table border=1  style="font-size: 1em;">
	<thead>
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Urgent</th>
			<th>Ring Bell</th>
			<th>Quantity</th>
			<th>Start</th>
			<th>Location</th>
			<th>Email</th>
			<th>SMS</th>
			<th>Copy</th>
			<th>Cost</th>
			<th>Charge</th>
			<th>Balance</th>
			<th>Advance</th>
			<th>Instructions</th>
			<th>Bill Visits</th>
		</tr>
	</thead>
	<tbody>';
	# Find all the payment records which are for item received in url and also which are not complete
	$allPaymentRecordsCursor =  $_SESSION ['mongo_database']->item_payment->find (array(
		'$and' => array(
			array('item' => $GLOBALS['itemRecordId']),
			array('distribution_complete' => $completedPayments)
		)
	));
	$allPaymentRecordsCursor->sort(array('delivery.location_code' => 1));
	debugPrintArray($allPaymentRecordsCursor,'allPaymentRecordsCursor');

	foreach ( $allPaymentRecordsCursor as $paymentDoc ) {
		debugPrintArray($paymentDoc,'paymentDoc');
		printTodayDistributionRecord($paymentDoc, $completedPayments);
	} # foreach ( $allPaymentRecordsCursor as $paymentDoc ) 
	echo '</tbody></table>';
	printTotals();
	echo getSmsLinkSpaced(
		'+919929941606,+917062667175',
		$pendingAmountSms,
		'To Income Collectors'
	);
}

printTodayPayments('False');
?>

<?php
	$allProductionRecords =  $_SESSION ['mongo_database']->animal_production->find (array(
		'$and' => array(
			array('used_for.item' => $itemRecordId)
		)
	));
	$totalProductionQuantity = 0;
	foreach ( $allProductionRecords as $productionDoc ) {
		$totalProductionQuantity = $totalProductionQuantity + $productionDoc['quantity'];
	}
	
?>


<hr /><b>Tables for SMS to production and distribution</b>
<?php $totalQuantity = 0; foreach ($distributionTimeGroups as $key => $recordDocs) { ?>
<hr />
<table border=1  style="font-size: 1em;">
	<thead>
		<tr>
			<th><span title="Quantity">Q</span></th>
			<th><span title="Location">L</span></th>
			<th><span title="Urgent">U</span></th>
			<th><span title="Ring Bell">B</span></th>
		</tr>
	</thead>
	<tbody>
<?php
	$totalQuantity = 0;
	$totalAbsentQuantity = 0;
	$rStr = '';
	foreach ( $recordDocs as $recordDoc ) {
		if ( getTimeGroupDateString($recordDoc) == $key) {

		if ($recordDoc['delivery_quantity'] <= 0) { continue; }
		/* echo "&#13;&#10;"; /* new line char for SMS */
		$rStr .= "%0A" . $recordDoc['delivery_quantity'] . ' | ' . $recordDoc['delivery_location_code'];
		echo getSmsRow ($recordDoc);

		if (array_key_exists('delivery_quantity',$recordDoc)){
			$totalQuantity = $totalQuantity + $recordDoc['delivery_quantity'];
		}
		if ($recordDoc['delivery_quantity'] == 0 && array_key_exists('orig_delivery_quantity',$recordDoc)){
			$totalAbsentQuantity = $totalAbsentQuantity + $recordDoc['orig_delivery_quantity'];
		}
		}
	}
?>
</tbody>
</table>
<span style="color: blue;"><?php echo $key ?> </span>
<br />Total <?php echo number_format($totalQuantity, 1, '.', '') . ' ' . substr($recordDoc['delivery_quantity_unit'],0,1); ?>
<br /> Absent <?php echo number_format($totalAbsentQuantity, 1, '.', '') . ' ' . substr($recordDoc['delivery_quantity_unit'],0,1); ?> 

<?php 
	$rStr .= '%0ATotal: ' . $totalQuantity . ' on ' . $key; 
	echo getSmsLinkSpaced(
		'+919929941606,+917062667175',
		$rStr,
		'To Distributors'
	);
?>

<!-- Sent at <?php echo date('Y-M-d h:i A'); ?> -->
<?php } # foreach (array_expression as $key => $value) ?>

<?php printTodayPayments(); ?>

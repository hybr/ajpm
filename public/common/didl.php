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

include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
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

$errorMessage = '';

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

if (!isset($urlArgsArray ['p']) || $urlArgsArray ['p'] == '') {
	$errorMessage .= "Access code is missing";
} else {
	if ($urlArgsArray ['p'] != md5($itemRecord['daily_distribution_report_password'])) {
		$errorMessage .= "Access code is wrong";
	}
}

# Report header
echo $_SESSION['url_domain_org']['name'] . '<br />' . $_SESSION['url_domain_org']['statement'];
echo '<hr /><b>Distribution List for Manager</b><br />';
echo "Item: " . $itemRecord['title'];

# Show error and exit
if ($errorMessage != '') { echo $errorMessage; exit; }
 

debugPrintArray($allPaymentRecordsCursor,'allPaymentRecordsCursor');

$todayStart = New Mongodate(strtotime('today'));
$tomorrowEnd   = New Mongodate(strtotime("tomorrow + 1day"));
debugPrintArray($todayStart->sec,'todayStart');
debugPrintArray($tomorrowEnd->sec,'tomorrowEnd');

$distributionTimeGroups = array();
$totalQuantity = 0;
$totalAbsentQuantity = 0;
$totalDue = 0;
$totalExtra = 0;
$totalCost = 0;
$totalOtherAmount = 0;
$oneDateStamp = '';
$currencySymbol = '';
$quantityUnit = '';

?>

<ul>
<li>All tables are ordered by location.</li>
<li>Charge is home delivery expense.</li>
<li>Click on quantity to create the exception record</li>
<li>Click on balance to update the payment record</li>
<li>Click on location to see detail bill as customer see it</li>
</ul>

<hr /><b>Table of all dsitributions.</b>

<table border=1  style="font-size: 1em;">
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
	<tbody>
<?php

function getCellStart($contentType = '') {
	if ($contentType == 'currency') {
		return '<td  style="text-align: right;" >';
	} else {
		return '<td>';
	}
}

function getCellEnd() {
	return '</td>';
}

function getRowStart() {
	return '<tr>';
}

function getRowEnd() {
	return '</tr>';
}

function getAsCell($text = '', $contentType = '') {
	return getCellStart($contentType) . $text . getCellEnd();
}

function getCurrency($amount) {
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
	return getAsCell(date('H:i a', $recordDoc['delivery_distribution_time']->sec));
}

function getUrgentCell($recordDoc) {
	$a = ($recordDoc['delivery_is_urgent'] == 'True')?'Yes':'';
	return getAsCell($a);
}

function getRingBellCell($recordDoc) {
	$a = ($recordDoc['delivery_do_ring_bell'] == 'True')?'':'No';
	return getAsCell($a);
}

function getLink($link = '', $text = '') {
	return '<a target="_bkank" href="' . $link . '">' . $text . '</a>';
}

function getQuantityCell($recordDoc) {
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] . '/item_daily_distribution_exception/create/All',
		        getAlertText(
				number_format($recordDoc['delivery_quantity'], 1, '.', '') . ' ' . $recordDoc['delivery_quantity_unit'],
				($recordDoc['delivery_quantity'] <= 0) 
			)
		)
	);
}

function getStartDateCell($recordDoc, $paymentDoc) {
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
	return getAsCell(
		getLink(
			'sms:?body='
			.date('Y-M', $paymentDoc['start_date']->sec)
			.'%20HYBR%20Farm%20milk%20bill%20link%0Ahttp://farm.hybr.in/common/idr.php?c='
			.$recordDoc['payment_record'].'%0AThanks%0A',
			'SMS'
		)
	);
}

function getCopyCell($recordDoc, $paymentDoc) {
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] 
			. '/item_daily_distribution_payment/copy/All?id=' . $paymentDoc['_id'],
			'Copy'
		)
	);
}

function getCostCell($recordDoc) {
	return getAsCell(
		getCurrency(($recordDoc['ipsr_amount']/$recordDoc['ipsr_quantity']) * $recordDoc['delivery_quantity']),
		'currency'
	);
}

function getChargeCell($recordDoc) {
	return getAsCell(
		getCurrency(
			($recordDoc['ipsr_daily_distribution_charge_per_visit'] 
			+ ($recordDoc['ipsr_daily_distribution_charge_per_unit'] * $recordDoc['delivery_quantity']))
		),
		'currency'
	);
}

function getBalanceCell($recordDoc, $paymentDoc) {
	return getAsCell(
		getLink(
			'http://admin.' . $_SESSION['url_domain'] . '/item_daily_distribution_payment/update/All?id=' . $paymentDoc['_id'],
		        getAlertText(
				getCurrency($recordDoc['payment_balance'] + $recordDoc['other_amount']),
				($recordDoc['payment_balance'] < 0) 
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
	$rStr .= getQuantityCell($recordDoc);
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

# Find the distribution records for each payment
$locale='en-US'; //browser or user locale

# Find all the payment records which are for item received in url and also which are not complete
$allPaymentRecordsCursor =  $_SESSION ['mongo_database']->item_daily_distribution_payment->find (array(
	'$and' => array(
		array('item' => new MongoId((string)(trim($urlArgsArray ['i'])))),
		array('distribution_complete' => 'False')
	)
));
$allPaymentRecordsCursor->sort(array('delivery.location_code' => 1));
foreach ( $allPaymentRecordsCursor as $paymentDoc ) {
	debugPrintArray($paymentDoc,'paymentDoc');

	if ($paymentDoc['distribution_complete'] == 'True') { continue; }

	# read distribution records for this payment
	$distributionRecordsCursor = $_SESSION ['mongo_database']
		->item_daily_distribution_record->find (array(
			'$and' => array(
				array('date' => array('$gte' => $todayStart)),
				array('payment_record' => $paymentDoc['_id'])
			)
	));
	# 'item' => $itemRecord['_id']
	/* "date" => array('$gte' => $todayStart, '$lte' => $tomorrowEnd) */
	$distributionRecordsCursor->sort(array('date' => 1));
	debugPrintArray($distributionRecordsCursor,'distributionRecordsCursor');

	# process each distribution record
	foreach ( $distributionRecordsCursor as $recordDoc ) {
		debugPrintArray($recordDoc,'recordDoc');

		/* show only today record */
		if (date('Y-M-d',$recordDoc['date']->sec) == date('Y-M-d',$todayStart->sec)) { continue; }

		
		/* grouping of records for SMS tables */
		$dateString = date('Y-M-d D',$recordDoc['date']->sec) . ' ' 
			. date('H:i',$recordDoc['delivery_distribution_time']->sec);
		if (!array_key_exists($dateString, $distributionTimeGroups)) {
			$distributionTimeGroups[$dateString] = array();
		}
		array_push($distributionTimeGroups[$dateString], $recordDoc);

		/* show a row */
		echo getRow($recordDoc, $paymentDoc);

		$totalOtherAmount += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] < 0) {
			$totalDue += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] > 0) {
			$totalExtra += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}

		if (array_key_exists('delivery_quantity',$recordDoc)){
			$totalQuantity = $totalQuantity + $recordDoc['delivery_quantity'];
		}
		if ($recordDoc['delivery_quantity'] == 0 && array_key_exists('orig_delivery_quantity',$recordDoc)){
			$totalAbsentQuantity = $totalAbsentQuantity + $recordDoc['orig_delivery_quantity'];
		}
		$totalCost = $totalCost + (($recordDoc['ipsr_amount']/$recordDoc['ipsr_quantity']) * $recordDoc['delivery_quantity']);
		$quantityUnit = $recordDoc['delivery_quantity_unit'];
	} # foreach ( $distributionRecordsCursor as $recordDoc ) 

} # foreach ( $allPaymentRecordsCursor as $paymentDoc ) 
$dueText = 'Due';
if ($totalOtherAmount > 0) $dueText = 'Extra';
?>
	</tbody>
</table>
<br />Total today sale <?php echo number_format($totalQuantity, 1, '.', ',') . ' ' . $quantityUnit; ?>
<br />Absent <?php echo number_format($totalAbsentQuantity, 1, '.', '') . ' ' . $quantityUnit; ?>
<br />Daily Income <?php echo $currencySymbol . number_format($totalCost, 2, '.', ','); ?> 
<br />Approximate monthly: <?php echo $currencySymbol . number_format($totalCost*30, 2, '.', ','); ?> 
<br /><?php echo 'Due: ' . $currencySymbol . number_format($totalDue, 2, '.', ','); ?>
<br /><?php echo 'Extra: ' . $currencySymbol . number_format($totalExtra, 2, '.', ','); ?>
<br /><?php echo 'Effective ' . $dueText . ' Amount: ' . $currencySymbol . number_format($totalOtherAmount, 2, '.', ','); ?> 

<?php
	# Find all the payment records which are for item received in url and also which are not complete
	$allProductionRecords =  $_SESSION ['mongo_database']->animal_production->find (array(
		'$and' => array(
			array('used_for.item' => new MongoId((string)(trim($urlArgsArray ['i'])))),
		)
	));
	$totalProductionQuantity = 0;
	foreach ( $allProductionRecords as $productionDoc ) {
		$totalProductionQuantity = $totalProductionQuantity + $productionDoc['quantity'];
	}
	
?>
<hr />

<b>Tables for SMS to production and distribution</b>
<?php $totalQuantity = 0; foreach ($distributionTimeGroups as $key => $recordDocs) { ?>
<hr />
<table border=1  style="font-size: 1em;">
	<thead>
		<tr>
			<th><span title="Urgent">U</span></th>
			<th><span title="Ring Bell">B</span></th>
			<th><span title="Quantity">Q</span></th>
			<th><span title="Location">L</span></th>
			<th><span title="Pending Amount">PA</span></th>
			<th><span title="Instructions">I</span></tr>
		</tr>
	</thead>
	<tbody>
<?php
	$totalQuantity = 0;
	$totalAbsentQuantity = 0;
	foreach ( $recordDocs as $recordDoc ) {
		if ( date('Y-M-d D',$recordDoc['date']->sec) . ' ' .date('H:i',$recordDoc['delivery_distribution_time']->sec) == $key) {

		if ($recordDoc['delivery_quantity'] <= 0) { continue; }
		/* echo "&#13;&#10;"; /* new line char for SMS */

		$a = ($recordDoc['delivery_is_urgent'] == 'True')?'Y':'N';
		echo '<tr><td>' . $a;

		$a = ($recordDoc['delivery_do_ring_bell'] == 'True')?'Y':'N';
		echo '|</td><td>' . $a;


		echo '|</td><td>';
		echo number_format($recordDoc['delivery_quantity'], 1, '.', '') . substr($recordDoc['delivery_quantity_unit'],0,1);

		echo '|</td><td>' . $recordDoc['delivery_location_code'];

		echo '|</td><td>' . $recordDoc['other_amount'];
		echo '|</td><td>' . $recordDoc['instructions'];
		echo '|</td></tr>';
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
Distribute at <span style="color: blue;"><?php echo $key ?> </span> |  
Total <?php echo number_format($totalQuantity, 1, '.', '') . ' ' . substr($recordDoc['delivery_quantity_unit'],0,1); ?> |  
Absent <?php echo number_format($totalAbsentQuantity, 1, '.', '') . ' ' . substr($recordDoc['delivery_quantity_unit'],0,1); ?> 
<br />
<!-- Sent at <?php echo date('Y-M-d H:i'); ?> -->
<?php } # foreach (array_expression as $key => $value) ?>


<hr />
<b>Payment Methods</b><ul>
<?php
	$paymentReceivingMethods = $_SESSION ['mongo_database']->payment_receiving_method->find (array(
		'for_org' => $_SESSION['url_domain_org']['_id']
	));
	foreach ( $paymentReceivingMethods as $doc ) {
		echo '<li>Pay by ' . $doc['by'] . ' at ' . $doc['note'] . '</li>';
	}
?>
</ul>

<table border=1  style="font-size: 1em;">
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
	<tbody>
<?php
$distributionTimeGroups = array();
$totalQuantity = 0;
$totalAbsentQuantity = 0;
$totalDue = 0;
$totalExtra = 0;
$totalCost = 0;
$totalOtherAmount = 0;
# Find all the payment records which are for item received in url and also which are not complete
$allPaymentRecordsCursor =  $_SESSION ['mongo_database']->item_daily_distribution_payment->find (array(
	'$and' => array(
		array('item' => new MongoId((string)(trim($urlArgsArray ['i'])))),
		array('distribution_complete' => 'True')
	)
));
$allPaymentRecordsCursor->sort(array('payment_balance' => 1, 'start_date' => -1, 'delivery.location_code' => 1));
foreach ( $allPaymentRecordsCursor as $paymentDoc ) {
	debugPrintArray($paymentDoc,'paymentDoc');

	if ($paymentDoc['distribution_complete'] == 'False') { continue; }

	# read distribution records for this payment
	$distributionRecordsCursor = $_SESSION ['mongo_database']
		->item_daily_distribution_record->find (array(
			'$and' => array(
				array('date' => array('$gte' => $todayStart)),
				array('payment_record' => $paymentDoc['_id'])
			)
	));
	# 'item' => $itemRecord['_id']
	/* "date" => array('$gte' => $todayStart, '$lte' => $tomorrowEnd) */
	$distributionRecordsCursor->sort(array('date' => 1));
	debugPrintArray($distributionRecordsCursor,'distributionRecordsCursor');

	# process each distribution record
	foreach ( $distributionRecordsCursor as $recordDoc ) {
		debugPrintArray($recordDoc,'recordDoc');

		/* show only today record */
		if (date('Y-M-d',$recordDoc['date']->sec) == date('Y-M-d',$todayStart->sec)) { continue; }

		
		/* grouping of records for SMS tables */
		$dateString = date('Y-M-d D',$recordDoc['date']->sec) . ' ' 
			. date('H:i',$recordDoc['delivery_distribution_time']->sec);
		if (!array_key_exists($dateString, $distributionTimeGroups)) {
			$distributionTimeGroups[$dateString] = array();
		}
		array_push($distributionTimeGroups[$dateString], $recordDoc);

		/* show a row */
		echo getRow($recordDoc, $paymentDoc);

		$totalOtherAmount += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] < 0) {
			$totalDue += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}
		if ($recordDoc['other_amount'] + $recordDoc['payment_balance'] > 0) {
			$totalExtra += $recordDoc['other_amount'] + $recordDoc['payment_balance'];
		}

		if (array_key_exists('delivery_quantity',$recordDoc)){
			$totalQuantity = $totalQuantity + $recordDoc['delivery_quantity'];
		}
		if ($recordDoc['delivery_quantity'] == 0 && array_key_exists('orig_delivery_quantity',$recordDoc)){
			$totalAbsentQuantity = $totalAbsentQuantity + $recordDoc['orig_delivery_quantity'];
		}
		$totalCost = $totalCost + (($recordDoc['ipsr_amount']/$recordDoc['ipsr_quantity']) * $recordDoc['delivery_quantity']);
		$quantityUnit = $recordDoc['delivery_quantity_unit'];
	} # foreach ( $distributionRecordsCursor as $recordDoc ) 

} # foreach ( $allPaymentRecordsCursor as $paymentDoc ) 
?>
	</tbody>
</table>
<br />Total today sale <?php echo number_format($totalQuantity, 1, '.', ',') . ' ' . $quantityUnit; ?>
<br />Absent <?php echo number_format($totalAbsentQuantity, 1, '.', '') . ' ' . $quantityUnit; ?>
<br />Daily Income <?php echo $currencySymbol . number_format($totalCost, 2, '.', ','); ?> 
<br />Approximate monthly: <?php echo $currencySymbol . number_format($totalCost*30, 2, '.', ','); ?> 
<br /><?php echo 'Due: ' . $currencySymbol . number_format($totalDue, 2, '.', ','); ?>
<br /><?php echo 'Extra: ' . $currencySymbol . number_format($totalExtra, 2, '.', ','); ?>
<br /><?php echo 'Effective ' . $dueText . ' Amount: ' . $currencySymbol . number_format($totalOtherAmount, 2, '.', ','); ?> 

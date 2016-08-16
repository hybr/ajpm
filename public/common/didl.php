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

debugPrintArray($urlArgsArray,'urlArgsArray');
?>

<?php echo $_SESSION['url_domain_org']['name'] ?>
<br /><?php echo $_SESSION['url_domain_org']['statement'] ?>
<hr />

<b>Distribution List for Manager</b><br />

<?php if ($errorMessage != '') { ?>
	<?php echo $errorMessage; exit; ?>
<?php } /* if ($errorMessage == '') */ ?>

<?php
	echo "Item: " . $itemRecord['title'];
	debugPrintArray($_SESSION,'_SESSION');
	$startOfDay = New Mongodate(strtotime('today'));
	$endOfDay   = New Mongodate(strtotime("tomorrow + 1day"));

	debugPrintArray($startOfDay->sec,'startOfDay');
	debugPrintArray($endOfDay->sec,'endOfDay');

	$distributionRecordsCursor = $_SESSION ['mongo_database']->item_daily_distribution_record->find (array(
		"date" => array('$gte' => $startOfDay)
	));
		/* "date" => array('$gte' => $startOfDay, '$lte' => $endOfDay) */
	$distributionRecordsCursor->sort(array('delivery_location_code' => 1));
	debugPrintArray($distributionRecordsCursor,'distributionRecordsCursor');
?>

<ul>
<li>All tables are ordered by location.</li>
<li>Charge is home delivery expense.</li>
</ul>

<hr />
<b>Table of all dsitributions.</b>
<table border=1  style="font-size: 1em;">
	<thead>
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Urgent</th>
			<th>Ring Bell</th>
			<th>Quantity</th>
			<th>Location</th>
			<th>Cost</th>
			<th>Charge</th>
			<th>Balance</th>
			<th>Advance</th>
			<th>Past</th>
			<th>Notes</th>
			<th>Email</th>
		</tr>
	</thead>
	<tbody>
<?php

	$distributionTimeGroups = array();
	$totalQuantity = 0;
	$totalCost = 0;
	$totalOtherAmount = 0;
	$oneDateStamp = '';
	foreach ( $distributionRecordsCursor as $doc ) {
		array_push($distributionTimeGroups{date('Y-M-d D',$doc['date']->sec) . ' ' .date('H:i',$doc['delivery_distribution_time']->sec)}, $doc['_id']);
		if ($oneDateStamp == '') {
			/* To show only one day records */
			$oneDateStamp = $doc['date']->sec;
		}

		if ($doc['date']->sec == $oneDateStamp) { continue; }
		echo '<tr>';
		echo '<td>' . date('Y-M-d D', $doc['date']->sec);
		echo '</td><td>' . date('H:i a', $doc['delivery_distribution_time']->sec);
		$a = ($doc['delivery_is_urgent'] == 'True')?'Yes':'';
		echo '</td><td>' . $a;
		$a = ($doc['delivery_do_ring_bell'] == 'True')?'':'No';
		echo '</td><td>' . $a;

                echo '</td><td><span style="color:';
                if ($doc['delivery_quantity'] <= 0) {
                        echo 'red;font-weight: bold;"';
                } else {
                        echo 'black;"';
                }
                echo '>'
                        . number_format($doc['delivery_quantity'], 1, '.', '')
                        . substr($doc['delivery_quantity_unit'],0,1) 
                        . '</span>';


		echo '</td><td>' . '<a target="_blank" href="/common/idr.php?c=' . $doc['payment_record'] 
			. '">' . $doc['delivery_location_code'] . '</a>';

		echo '</td><td style="text-align: right;">' . number_format(($doc['ipsr_amount']/$doc['ipsr_quantity']) 
			* $doc['delivery_quantity'],2,'.',',') . ' ' . $doc['ipsr_amount_currency'];

		echo '</td><td style="text-align: right;">' . number_format(($doc['ipsr_daily_distribution_charge_per_visit'] 
			+ ($doc['ipsr_daily_distribution_charge_per_unit'] * $doc['delivery_quantity'])),2,'.',',') 
			. ' ' . $doc['ipsr_amount_currency'];

		echo '</td><td style="text-align: right;">' . number_format($doc['payment_balance'],2,'.',',') . ' ' 
			. $doc['ipsr_amount_currency'];

		$a = ($doc['paid_as_advance'] == 'True')?'':'No';
		echo '</td><td>' . $a;
		echo '</td><td><span style="color:';
		if ($doc['other_amount'] < 0) {
			echo 'red';
		} else {
			echo 'green';
		}
		echo  ';">' . $doc['other_amount'] . '</span>';
		$totalOtherAmount += $doc['other_amount'];
		echo '</td><td>' . $doc['instructions'];
		echo '</td><td>' . '<a target="_blank" href="mailto:customer_email_address?subject=Link%20to%20HYBR%20Farm%20milk%20bill%20for%20'.$doc['delivery_location'].'&body='.$doc['delivery_location'].',%0APlease%20chek%20following%20link%20for%20bill%20details.%0Ahttp://farm.hybr.in/common/idr.php?c='.$doc['payment_record'].'%0AThanks%0A'.$_SESSION['url_domain_org']['name'].'">Send</a>';
		echo '</td></tr>';
		$totalQuantity = $totalQuantity + $doc['delivery_quantity'];
		$totalCost = $totalCost + (($doc['ipsr_amount']/$doc['ipsr_quantity']) * $doc['delivery_quantity']);
	}
?>
	</tbody>
</table>
Total today sale <?php echo number_format($totalQuantity, 1, '.', ',') . ' ' . $doc['delivery_quantity_unit']; ?>
 and <?php echo number_format($totalCost, 2, '.', ',') . ' ' . $doc['ipsr_amount_currency']; ?> 
  | Approximate monthly: <?php echo number_format($totalCost*30, 2, '.', ',') . ' ' . $doc['ipsr_amount_currency']; ?> 
 | Due Amount: <?php echo number_format($totalOtherAmount, 2, '.', ',') . ' ' . $doc['ipsr_amount_currency']; ?> <br />
<hr />

<b>Tables for SMS to production and distribution</b>
<?php $totalQuantity = 0; foreach ($distributionTimeGroups as $key => $value) { ?>
<hr />
<table border=1  style="font-size: 1em;">
	<thead>
		<tr>
			<th><span title="Urgent">U</span></th>
			<th><span title="Ring Bell">B</span></th>
			<th><span title="Location">L</span></th>
			<th><span title="Quantity">Q</span></th>
			<th><span title="Pending Amount">PA</span></th>
			<th><span title="Instructions">I</span></th>
		</tr>
	</thead>
	<tbody>
<?php
	$totalQuantity = 0;
	foreach ( $distributionRecordsCursor as $doc ) {
		if ( date('Y-M-d D',$doc['date']->sec) . ' ' .date('H:i',$doc['delivery_distribution_time']->sec) == $key) {

		$a = ($doc['delivery_is_urgent'] == 'True')?'Y':'N';
		echo '<tr><td>-----|' . $a;

		$a = ($doc['delivery_do_ring_bell'] == 'True')?'Y':'N';
		echo '|</td><td>' . $a;

		echo '|</td><td>' . $doc['delivery_location_code'];

		echo '|</td><td><span style="color:';
		if ($doc['delivery_quantity'] <= 0) {
			echo 'red;font-weight: bold;"';
		} else {
			echo 'black;"';
		}
			# . substr($doc['delivery_quantity_unit'],0,1) 
		echo '>' 
			. number_format($doc['delivery_quantity'], 1, '.', '')
			. '</span>';

		echo '|</td><td>' . $doc['other_amount'];
		echo '|</td><td>' . $doc['instructions'];
		echo '|</td></tr>' . "\n\n";
		$totalQuantity = $totalQuantity + $doc['delivery_quantity'];
		}
	}
?>
	</tbody>
</table>
Distribute at <span style="color: blue;"><?php echo $key ?> </span> 
Total <?php echo number_format($totalQuantity, 1, '.', '') . ' ' . substr($doc['delivery_quantity_unit'],0,1); ?> <br />
<!-- Sent at <?php echo date('Y-M-d H:i'); ?> -->
<?php } # foreach (array_expression as $key => $value) ?>


<hr />
<b>Payment Methods</b><ul>
<?php
	$paymentReceivingMethods = $_SESSION ['mongo_database']->payment_receiving_method->find (array(
	));
	foreach ( $paymentReceivingMethods as $doc ) {
		echo '<li>Pay by ' . $doc['by'] . ' at ' . $doc['note'] . '</li>';
	}
?>
</ul>

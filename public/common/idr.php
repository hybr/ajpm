<?php

/**
 * session_start() creates a session or resumes the current one based on a 
 * session identifier passed via a GET or POST request, or passed via a cookie.
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

if (!isset($urlArgsArray ['c']) || $urlArgsArray ['c'] == '') {
	$errorMessage = "Code is missing in the URL request";
}

if (!isValidMongoObjectID($urlArgsArray ['c'])) {
	$errorMessage = "Wrong code";
}

debugPrintArray($urlArgsArray,'urlArgsArray');
?>

<?php echo $_SESSION['url_domain_org']['name'] ?>
<br /><?php echo $_SESSION['url_domain_org']['statement'] ?>
<hr />

<?php if ($errorMessage != '') { ?>
	<?php echo $errorMessage; exit; ?>
<?php } /* if ($errorMessage == '') */ ?>

<?php
	debugPrintArray($_SESSION,'_SESSION');
	$paymentRecord = $_SESSION ['mongo_database']
	->item_daily_distribution_payment->findOne (array(
		'$and' => array (
			array ('_id' => new MongoId ( $urlArgsArray ['c'] )),
			array ('for_org' => new MongoId ( 
				$_SESSION ['url_domain_org'] ['_id'] 
			))
		)
	));
	debugPrintArray($paymentRecord,'paymentRecord');

	if (! isset($paymentRecord)) {
		echo "Invalid code."; exit;
	}

	$itemRecord = $_SESSION ['mongo_database']->item->findOne (array(
		'$and' => array (
			array ('_id' => new MongoId ( $paymentRecord ['item'] )),
			array ('for_org' => new MongoId ( 
				$_SESSION ['url_domain_org'] ['_id']
			))
		)
	));
	echo "<b>Distribution Report of <u>" . $itemRecord['title'] . "</u></b>";
	echo "<br />" . $itemRecord['summary'];
	echo '<hr />Right now time is : ' . date('D Y-M-d H:i');
	echo '<br />Location: ';
	foreach ($paymentRecord['delivery'] as $key => $value) {
		echo $value['location'] . ' | ';
	}

	echo '<br />Payment: <b>' . $paymentRecord['paid_amount'] .  ' ' . $paymentRecord['paid_amount_currency']  . '</b>';
	if ($paymentRecord['paid_as_advance'] == 'True') {
		echo ' (payment is paid as advance on '. date('D Y-M-d',$paymentRecord['paid_date']->sec) . ')';
	} else {
		echo '( <font color="red"><u><b>advance payment is pending</b></u></font> )';
	}

	$distributionRecordsCursor = $_SESSION ['mongo_database']
		->item_daily_distribution_record->find (array(
			'payment_record' => new MongoId ( $urlArgsArray ['c'] )
		))
	;
	$distributionRecordsCursor->sort(array('date' => 1));
?>
<ul>
<li>Charge is home delivery expense.</li>
</ul>

<table border=1 style="font-size: 1.5em;">
	<thead>
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Quantity</th>
			<th>Rate</th>
			<th>Cost</th>
			<th>Charge</th>
			<th>Balance</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php
	$due = 0;
	$explanation = '';
	foreach ( $distributionRecordsCursor as $doc ) {
		echo '<tr><td>' . date('Y-M-d D', $doc['date']->sec);
		echo '</td><td>' . date('H:i', $doc['delivery_distribution_time']->sec);
		echo '</td><td>' . $doc['delivery_quantity'] . ' ' . $doc['delivery_quantity_unit'];

		echo '</td><td>' . $doc['ipsr_amount'] . ' ' . $doc['ipsr_amount_currency'] . ' per ' 
			. $doc['ipsr_quantity'] . ' ' . $doc['ipsr_quantity_unit'];
		echo '</td><td>' . ($doc['ipsr_amount']/$doc['ipsr_quantity']) * $doc['delivery_quantity'] 
			. ' ' . $doc['ipsr_amount_currency'];
		echo '</td><td>' . ($doc['ipsr_daily_distribution_charge_per_visit'] 
			+ ($doc['ipsr_daily_distribution_charge_per_unit'] * $doc['delivery_quantity'])) . ' ' . $doc['ipsr_amount_currency'];
		echo '</td><td>' . $doc['payment_balance'] . ' ' . $doc['ipsr_amount_currency'];
		echo '</td><td>' . $doc['instructions'];
		echo '</td></tr>';
		$due = $doc['other_amount'];
		$explanation = $doc['other_amount_explanation'];
	}
?>
	</tbody>
</table>
</br />
Our distribution details show one day advance for your and our planning purpose.
<br />
<?php if ($due >= 0) { ?>
	<span style="color: green;">
<?php } else { ?>
	<span style="color: red;">
<?php } ?>
	Due Amount: <b><?php echo $due; ?> <?php echo $paymentRecord['paid_amount_currency']; ?></b>
	<?php echo $explanation; ?>
</span>

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


<?php

/* Item Order Report */

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

$errorMessage = '';

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

/*
if (!isset($urlArgsArray ['c']) || $urlArgsArray ['c'] == '') {
	$errorMessage = "Code is missing in the URL request";
}

if (!isValidMongoObjectID($urlArgsArray ['c'])) {
	$errorMessage = "Wrong code";
}
*/

debugPrintArray($urlArgsArray,'urlArgsArray');
debugPrintArray($_SESSION,'_SESSION');

if (!(array_key_exists('allowed_as', $_SESSION) && $_SESSION['allowed_as'] == 'OWNER')) {
	$errorMessage = 'Not Auhtorised';
}
?>

<?php echo $_SESSION['url_domain_org']['name'] ?>
<br /><?php echo $_SESSION['url_domain_org']['statement'] ?>

<?php if ($errorMessage != '') { ?>
	<?php echo $errorMessage; exit; ?>
<?php } /* if ($errorMessage == '') */ ?>

<?php
	$itemOrderRecordsCursor= $_SESSION ['mongo_database']->item_order->find (array(
		'for_org' => $_SESSION ['url_domain_org'] ['_id']
	))->sort(array(
		'order_date' => 1
	));
	debugPrintArray($itemOrderRecordsCursor,'itemOrderRecordsCursor');

	if (! isset($itemOrderRecordsCursor)) {
		echo "Invalid code."; exit;
	}

?>

<hr /><b>Item Orders Report</b>

This is the report of all the orders. Today is  <?php echo $currentDate->format('Y-M-d'); ?>

<table border=1 >
	<thead>
		<tr>
			<th>Order Date</th>
			<th>Item</th>
			<th>Order By</th>
			<th>Quantity</th>
			<th>Delivery Location</th>
			<th>Delivery Frequency</th>
			<th>Payment Frequency</th>
		</tr>
	</thead>
	<tbody>
<?php

	foreach ( $itemOrderRecordsCursor as $itemOrderDoc ) {
		$thisAnimalRowStr = '';
		$deliveryComplete = 0;

		$thisAnimalRowStr .= getAsCell(
			getUpdateRecordLink(
				'item_order',
				$itemOrderDoc['_id'],
				date('D Y-m-d', $itemOrderDoc['order_date']->sec)
		        )
		);
		$itemDoc = getOneDocument('item', '_id',  $itemOrderDoc['item']);
		$thisAnimalRowStr .= getAsCell(
			getCreateRecordLink(
				'item_order',
				$itemDoc['title'],
				'?item=' . $itemOrderDoc['item']
		        )
		);
		$thisAnimalRowStr .= getAsCell(
			getPersonNamesById(getRecordFieldValue($itemOrderDoc, 'order_by'))
		);
		$thisAnimalRowStr .= getAsCell(
			getRecordFieldValue($itemOrderDoc, 'quantity') 
			. ' ' . getRecordFieldValue($itemOrderDoc, 'quantity_unit')
		);
		$thisAnimalRowStr .= getAsCell(
			getPersonNamesById(getRecordFieldValue($itemOrderDoc, 'delivery_location'))
		);
		$thisAnimalRowStr .= getAsCell(
			getPersonNamesById(getRecordFieldValue($itemOrderDoc, 'delivery_frequency'))
		);
		$thisAnimalRowStr .= getAsCell(
			getPersonNamesById(getRecordFieldValue($itemOrderDoc, 'payment_frequency'))
		);


		if ($deliveryComplete) {
			echo '<tr style="background-color: lightgray;">' . $thisAnimalRowStr . '</tr>';
		} else {
			echo '<tr>' . $thisAnimalRowStr . '</tr>';
		}
	}
?>
	</tbody>
</table>

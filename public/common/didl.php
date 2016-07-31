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
header ( 'Expires: ' . date () ); 

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
debugPrintArray($urlArgsArray,'urlArgsArray');
?>
<h1>Distribution List</h1>

<?php if ($errorMessage != '') { ?>
	<?php echo $errorMessage ?>
<?php } /* if ($errorMessage == '') */ ?>

<?php

	debugPrintArray($_SESSION,'_SESSION');
	$startOfDay = New Mongodate(strtotime('today'));
	$endOfDay   = New Mongodate(strtotime("tomorrow"));

	debugPrintArray($startOfDay->sec,'startOfDay');
	debugPrintArray($endOfDay->sec,'endOfDay');

	$distributionRecords = $_SESSION ['mongo_database']->item_daily_distribution_record->find (array(
		"date" => array('$gte' => $startOfDay, '$lte' => $endOfDay)
	));
	debugPrintArray($distributionRecords,'distributionRecords');
?>

<table border=1>
	<thead>
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Quantity</th>
			<th>Location</th>
			<th>Cost</th>
			<th>Balance</th>
			<th>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php

	foreach ( $distributionRecords as $doc ) {
		echo '<tr>';
		echo '<td>' . date('Y-M-d', $doc['date']->sec) . '</td>';
		echo '<td>' . date('H:i a', $doc['distribution_time']->sec) . '</td>';
		echo '<td>' . $doc['daily_quantity'] . ' ' . $doc['daily_quantity_unit'] . '</td>';
		echo '<td>' . $doc['delivery_location'];
		echo '<td>' . ($doc['rate_amount']/$doc['rate_quantity']) * $doc['daily_quantity'] . ' ' . $doc['rate_amount_currency'] . '</td>';
		echo '<td>' . $doc['payment_balance'] . ' ' . $doc['rate_amount_currency'] . '</td>';
		echo '<td>' . $doc['instructions'] . '</td>';
		echo '</tr>';
	}
?>
	</tbody>
</table>


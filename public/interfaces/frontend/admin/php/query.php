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
	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'backend'
 	. DIRECTORY_SEPARATOR . 'php'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
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
/* header ( 'Expires: ' . date () ); */

// The JSON standard MIME header.
header ( 'Content-type: application/json' );

if (empty($_SESSION ['user'])) {
	echo json_encode ( array (
		array (
			'label' => 'Please login first',
			'value' => '' 
		) 
	) );
	return;
}

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

$classForQuery = 'public_';
foreach ( split('_', $urlArgsArray ['c']) as $w ) {
	$classForQuery .= ucfirst ( strtolower ( $w ) );			
}
$actionInstance = new $classForQuery();
if (!isAllowed($_SESSION ['url_collection'], $_SESSION['url_sub_task'])) {
	echo json_encode ( array (
		array (
			'label' => $_SESSION['authorization_message'],
			'value' => ''
		)
	));
	return;
}

/* if request is for businesses then show the businesses user owners */
$arr = array ();
$sfs = split ( ",", $urlArgsArray ['sf'] );
$condition = array ();
$jsConf = '';
$searchConditions = array ();
$limit = 10;

/* create search conditions array */
foreach ( $sfs as $sf ) {
	if (strpos($sf, 'number') == false) {
		array_push ( $searchConditions, array (
			$sf => array (
					'$regex' => new MongoRegex ( "/" . $urlArgsArray ['p'] . "/i" ) 
			) 
		) );
	} else {
		array_push ( $searchConditions, array (
			$sf => (float)$urlArgsArray ['p'],
		) );
	}
} /* foreach ( $sfs as $sf ) */


/* there are few collections which are open for public, for rest add organization as conditions */
if (in_array($urlArgsArray ['c'], array('user', 'organization', 'database_domain', 'chart_of_accounts'))) {
	/* for public */
	$searchConditions = array(
		'$or' => $searchConditions
	);
} else {
	$id = '';
	if (isset($_SESSION ['url_domain_org']) && isset ( $_SESSION ['url_domain_org'] ['_id'] )) {
		$id = (string) $_SESSION ['url_domain_org'] ['_id'];
	} else {
		$id = '54c27c437f8b9a7a0d074be6'; /* owebp */
	}		
	$isOwnedByCurrentUrlDomain = array ('for_org' => new MongoId ( $id ));

	/* specific to the domain */
	$searchConditions = array(
		'$and' => array(
			$isOwnedByCurrentUrlDomain,
			array(
				'$or' => $searchConditions
			)
		)
	);
} /* if (in_array($urlArgsArray ['c'], */

// print_r($searchConditions); 
$findCursor = $_SESSION['mongo_database']->{$urlArgsArray ['c']}->find ($searchConditions);


$arr = array ();
$tfs = split ( ",", $urlArgsArray ['tf'] );
foreach ( $findCursor as $doc ) {
	array_push ( $arr, array (
		'label' => showSelectedReadOnlyFields($tfs, $doc, false, 'query'),
		'value' => ( string ) $doc ['_id'] 
	) );
}

if (empty($arr)) {
	array_push ( $arr, array (
		'label' => 'No Record Found',
		'value' => 'No Record Found',
	) );
}

echo json_encode ( $arr );

?>

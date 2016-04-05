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
define ( 'SERVER_SIDE_PUBLIC_DIR', __DIR__ . DIRECTORY_SEPARATOR);

define ( 'SERVER_SIDE_LIB_DIR', SERVER_SIDE_PUBLIC_DIR
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'lib'
	. DIRECTORY_SEPARATOR . 'server_side'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'lib'
	. DIRECTORY_SEPARATOR . 'single_page'
);
/**
 * Include the common files
 */
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'org_details.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'parse_action.php';
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

if (!isset($urlArgsArray ['p']) || $urlArgsArray ['p'] == '') {
	echo "{status : 'pattern to search is missing', result : ''}";
	exit;
}

$classForQuery = 'public_';
if(!isset($urlArgsArray ['c'])) {
	/* default area to search */
	$urlArgsArray ['c'] = 'web_page';
}
foreach ( split('_', $urlArgsArray ['c']) as $w ) {
	$classForQuery .= ucfirst ( strtolower ( $w ) );			
}
$actionInstance = new $classForQuery();
if (!isAllowed(array($actionInstance->myModuleName()), $_SESSION['url_sub_task'])) {
	echo "{status : 'No Access to " 
		. $_SESSION['url_task'] . '/' 
		.  $_SESSION['url_sub_task'] 
		. ", result : ''}";
	exit;
	return;
}

/* find 'searchable' => 1 fields */
function getSerchableFieldList ($fields, $parentFieldName = '' ) {
	$fieldsList = array();
	foreach ($fields as $fieldName => $fieldAttributes) {
		if (isset($fieldAttributes['type']) && $fieldAttributes['type'] == 'container') {
			$fieldsList = array_merge(
				$fieldsList, 
				getSerchableFieldList($fieldAttributes['fields'], $fieldName)
			);
		} else {
			if (isset($fieldAttributes['searchable']) && $fieldAttributes['searchable'] == 1) {
				if ($parentFieldName == '') {
					array_push($fieldsList, $fieldName);
				} else {
					array_push($fieldsList, $parentFieldName . '.' . $fieldName);
				}
			}
		}
	}
	return $fieldsList;
}



/* if request is for businesses then show the businesses user owners */
/* search fields */
$arr = array ();
$condition = array ();
$jsConf = '';
$searchConditions = array ();


/* create search conditions array  sf = search field */
foreach ( getSerchableFieldList($actionInstance->fields) as $sf ) {
	array_push ( $searchConditions, array (
			$sf => array (
					'$regex' => new MongoRegex ( "/" . $urlArgsArray ['p'] . "/i" ) 
			) 
	) );
} /* foreach */

if (empty($searchConditions)) {
	echo "{status : 'no searchable fields', result : ''}";
	exit;	
}

/* there are few collections which are open for public, for rest add organization as conditions */
if (in_array($urlArgsArray ['c'], array('user', 'person', 'organization', 'item'))) {
	/* for public */
	$searchConditions = array(
		'$or' => $searchConditions
	);
} else {
	$id = '';
	if (isset($_SESSION ['url_domain_org']) && isset ( $_SESSION ['url_domain_org'] ['_id'] )) {
		$id = $_SESSION ['url_domain_org'] ['_id'];
	} else {
		$id = '54c27c437f8b9a7a0d074be6'; /* owebp */
	}		
	$isOwnedByCurrentUrlDomain = array (
			'for_org' => new MongoId ( $id )
	);
	/* specific to the domain */
	$searchConditions = array(
			'$and' => array(
					$isOwnedByCurrentUrlDomain,
					array(
						'$or' => $searchConditions
					)
			)
	);
}


/*
echo "{status : 'OK', result : " . json_encode ($searchConditions) . "}";
exit;
*/

$limit = 10;
$skip = 0;
if (isset($urlArgsArray ['l'])) {
	$limit = $urlArgsArray ['l'];
}
if (isset($urlArgsArray ['s'])) {
	$skip = $urlArgsArray ['s'];
}
$findCursor = $_SESSION['mongo_database']
	->	{$urlArgsArray ['c']}
	->	find ($searchConditions)
	->	skip($skip)
	->	limit ( $limit );


$arr = array ();
foreach ( $findCursor as $doc ) {
	array_push ( $arr, $doc);
}
echo "{status : 'OK', result : " . json_encode ($arr) . ", conditions : " . json_encode($searchConditions) . "}";
exit;

?>

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
if (!isAllowed(array($actionInstance->myModuleName()), $_SESSION['url_sub_task'])) {
	echo json_encode ( array (
		array (
			'label' => 'No Access to ' . $_SESSION['url_task'] . '/' .  $_SESSION['url_sub_task'],
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
	array_push ( $searchConditions, array (
			$sf => array (
					'$regex' => new MongoRegex ( "/" . $urlArgsArray ['p'] . "/i" ) 
			) 
	) );
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

	/* print_r($searchConditions); */
	
	$findCursor = $_SESSION['mongo_database']->{$urlArgsArray ['c']}->find ($searchConditions)->limit ( $limit );


$arr = array ();
$tfs = split ( ",", $urlArgsArray ['tf'] );
foreach ( $findCursor as $doc ) {
	$label = '';
	foreach ( $tfs as $tf ) {
		if (!isset($doc[$tf])) {
			continue;
		}
		if (is_array ( $doc [$tf] )) {
			foreach ( $doc [$tf] as $subField ) {
				foreach ( $subField as $subElem => $val ) {
					$label .= $val . ' ';
				}
				$label = rtrim ( $label, " " );
				$label .= '; ';
			}
			$label = rtrim ( $label, "; " );
		} else {
			if(isset($doc[$tf]) && $doc[$tf] != '') {
				$label .= $doc [$tf] . ', ';
			}
		}
		$label .= ", ";
	}
	$label = rtrim ( $label, ", " );
	array_push ( $arr, array (
			'label' => $label,
			'value' => ( string ) $doc ['_id'] 
	) );
}

echo json_encode ( $arr );

?>

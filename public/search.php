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

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}



/* find 'searchable' => 1 fields */
function getSerchableFieldList ($fields, $parentFieldName = '' ) {
	$fieldsList = array();
	$searchResultTitleFieldNames = array();
	$searchResultDetailFieldNames = array();
		
	foreach ($fields as $fieldName => $fieldAttributes) {
		if (isset($fieldAttributes['type']) && $fieldAttributes['type'] == 'container') {
			$tas = getSerchableFieldList($fieldAttributes['fields'], $fieldName);
			$fieldsList = array_merge($fieldsList, $tas[0]);
			$searchResultTitleFieldNames = array_merge($searchResultTitleFieldNames, $tas[1]);
			$searchResultDetailFieldNames = array_merge($searchResultDetailFieldNames, $tas[2]);
		} else {
			if (isset($fieldAttributes['searchable']) && $fieldAttributes['searchable'] == 1) {
				if ($parentFieldName == '') {
					array_push($fieldsList, $fieldName);
				} else {
					array_push($fieldsList, $parentFieldName . '.' . $fieldName);
				}
			}
			if (isset($fieldAttributes['searchResultTitle']) && $fieldAttributes['searchResultTitle'] == 1) {
				if ($parentFieldName == '') {
					array_push($searchResultTitleFieldNames, $fieldName);
				} else {
					array_push($searchResultTitleFieldNames, $parentFieldName . '.' . $fieldName);
				}
			}
			if (isset($fieldAttributes['searchResultDetail']) && $fieldAttributes['searchResultDetail'] == 1) {
				if ($parentFieldName == '') {
					array_push($searchResultDetailFieldNames, $fieldName);
				} else {
					array_push($searchResultDetailFieldNames, $parentFieldName . '.' . $fieldName);
				}
			}			
		}
	}
	return array($fieldsList, $searchResultTitleFieldNames, $searchResultDetailFieldNames);
} /* function getSerchableFieldList ($fields, $parentFieldName = '' ) */


function getSearchConditionsForOneCollection($collectionName,$patternToSearch) {

	/* create instance of the table to search */
	$classForQuery = 'public_';
	foreach ( split('_', $collectionName) as $w ) {
		$classForQuery .= ucfirst ( strtolower ( $w ) );			
	}
	$actionInstance = new $classForQuery();

	/* get a list of fields to search */
	$tas =  getSerchableFieldList($actionInstance->fields);

	/* create search conditions array  sf = search field */
	$searchConditions = array ();
	foreach ( $tas[0] as $sf ) {
		array_push ( $searchConditions, array (
			$sf => array (
					'$regex' => new MongoRegex ( '/' . $patternToSearch . '/i' ) 
			) 
		));
	}

	if (empty($searchConditions)) {
		return array();
	}
	/* there are few collections which are open for public, for rest add organization as conditions */
	if (in_array($collectionName, array('user', 'person', 'organization', 'item'))) {
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

	return $searchConditions;
} /* function getSearchConditionsForOneCollection($collectionName,$patternToSearch) */


function searchInOneTable($collectionName = 'web_site', $patternToSearch) {
	$arr = array();
	$errorMessage = '';
	if (isset($urlArgsArray ['l'])) {
		$limit = $urlArgsArray ['l'];
	}
	if (isset($urlArgsArray ['s'])) {
		$skip = $urlArgsArray ['s'];
	}
	try {
		$conditions = getSearchConditionsForOneCollection($collectionName, $patternToSearch);
		if (!empty($conditions)) {
			$findCursor = $_SESSION['mongo_database']
				->{$collectionName}
				->find ($conditions);
			foreach ( $findCursor as $doc ) {
				$doc['collection_name'] = $collectionName;
				array_push ($arr, $doc);
			}
		}
	} catch (MongoCursorException $e) {
		$errorMessage = $e->getMessage();
	}


	return $arr;
} /* function searchInOneTable($collectionName = 'web_site') { */

$searchAreas = array();
function searchInAllTables($patternToSearch) {
	global $searchAreas;
	$arr = array();
	$tables = array( 'web_page', 'contact', 'item', 'item_catalog', 'person', 'real_estate_asset');
        foreach ($tables as $collectionName ) {
                if (!validDatabaseCollection($collectionName)) {
                         continue;
                }

		$searchResult = searchInOneTable($collectionName, $patternToSearch);
		if (!empty($searchResult)){
			$arr = array_merge($arr, $searchResult);
			array_push($searchAreas, $collectionName);
		}
	}
	return $arr;
}

if (!isset($urlArgsArray ['p']) || $urlArgsArray ['p'] == '') {
	echo '{"status" : "pattern to search is missing", "result" : ""}';
	exit;
}

if (!isAllowed($_SESSION ['url_action'], $_SESSION['url_sub_task'])) {
	echo '{"status" : ' . $_SESSION['authorization_message'] . ', "result" : ""}';
	exit;
}

echo '{"status" : "OK", "result" : ' 
	. json_encode (searchInAllTables($urlArgsArray ['p'])) 
	. ', "searchAreas" : ' .  json_encode($searchAreas) 
	. ', "errorMessage" : "' . $errorMessage . '"'
	. "}";

/*
echo '{"status" : "OK"'
	. ', "result" : ' . json_encode (searchInAllTables())
	. ', "conditions" : ' . json_encode($searchConditions)
	. ', "titleFields" : ' . json_encode($tas[1])
	. ', "detailFields" : ' . json_encode($tas[2])
	. ', "searchArea" : "' . $classForQuery . '"'
	. ', "errorMessage" : "' . $errorMessage . '"'
	. "}";
*/

exit;

?>

<?php

/* debug option */
if (isset ( $_GET ['debug'] )) {
	$_SESSION ['debug'] = true;
} else {
	$_SESSION ['debug'] = false;
}

/**
 * Convert a string in to title format
 * @param string $title string to be converted
 * @return string converted string in title format
 */
function getTitle($title) {
	$titleWordArray = split ( '_', $title );
	$returnString = '';
	foreach ( $titleWordArray as $titleWord ) {
		$returnString .= ' ' . ucfirst ( strtolower ( $titleWord ) );
	}
	return $returnString;
}

function debugPrintArray($a, $msg = '') {
	if (!$_SESSION['debug']) return;
	echo '<hr />';
	echo 'DEBUG of '. $msg .'<pre>';
	/*
	 * 
	$traces = debug_backtrace();
	foreach($traces as $trace) {
		echo "<br />called by {$trace['class']} :: {$trace['function']}";
	}
	*/
	echo '<hr />';
			print_r ( $a );
			echo '</pre>';
			echo '<hr />';
}

function getParamValue($key, $args) {
	if (isset($_POST [$key])) return $_POST [$key];
	if (isset($_GET [$key])) return $_GET [$key];
	if (isset($args [$key])) return $args [$key];
	return null;
}

function validDatabaseDomain($databaseDomainDoc) {
        if ($databaseDomainDoc['mandatory'] == 'True') {
                return true;
        }
        foreach ($_SESSION['mongo_database']->organization_database_domain->find() as $allowedDomain ) {
                if ( (string) $allowedDomain['organization'] == (string) $_SESSION ['url_domain_org']['_id']
                        && (string) $allowedDomain['domain'] == (string) $databaseDomainDoc['_id']
                ) {
                        return  true;
                }
        }
}


function validDatabaseCollection ($collectionName) {

	/* get the record of the collection */
	$databaseCollectionDoc = $_SESSION ['mongo_database']->database_collection->findOne ( array (
		'name' => $collectionName
        ) );

	if (empty($databaseCollectionDoc)) {
		/* no such collection exists */
		return false;
	}


	foreach( $databaseCollectionDoc['domain'] as $assignedDatabaseDomain) {

		/* find the database_domain record for the assigned database domains */
 		$databaseDomainDoc = $_SESSION ['mongo_database']->database_domain->findOne ( array (
			'_id' => new MongoId((string)(trim($assignedDatabaseDomain['name'])))
                ) );

		if (empty($databaseDomainDoc)) {
			/* assigned domain does not exists */
			return false;
		}

		return validDatabaseDomain($databaseDomainDoc);
	} /* foreach( $databaseCollectionDoc['domain'] as $assignedDatabaseDomain) */


	return false;
} /* function validDatabaseCollection */
?>

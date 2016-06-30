<?php

/**
 * Common function used by other php files
 */

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

function getParamValue($key, $args) {
	if (isset($_POST [$key])) return $_POST [$key];
	if (isset($_GET [$key])) return $_GET [$key];
	if (isset($args [$key])) return $args [$key];
	return null;
}

$_SESSION['authorization_message'] = '';

function validDatabaseDomain($databaseDomainDoc) {
	/* check if this domain is either mandatory or assigned to organization */
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
	
	$_SESSION['authorization_message'] = 'Module ' . $databaseDomainDoc['name'] . ' is not authorized';
	return false;
}


function validDatabaseCollection ($collectionName) {
	
	/* get the record of the collection */
	$_SESSION['collection'] = $_SESSION ['mongo_database']->database_collection->findOne ( array (
		'name' => $collectionName
	));

	if (empty($_SESSION['collection'])) {
		/* no such collection exists */
		$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Collection ' . $collectionName . ' does not exists';
		return false;
	}

	foreach( $_SESSION['collection']['domain'] as $assignedDatabaseDomain) {
		/* get the name of module which is associated with collection to be authorized */
		/* find the database_domain record for the assigned database domains */
 		$databaseDomainDoc = $_SESSION ['mongo_database']->database_domain->findOne ( array (
			'_id' => new MongoId((string)(trim($assignedDatabaseDomain['name'])))
		));

		if (empty($databaseDomainDoc)) {
			/* assigned domain does not exists */
			$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Assigned module ' . $assignedDatabaseDomain['name'] . ' does not exists';
			return false;
		}

		
		if (validDatabaseDomain($databaseDomainDoc)) {
			return true;
		}
	} /* foreach( $databaseCollectionDoc['domain'] as $assignedDatabaseDomain) */

	$_SESSION['authorization_message'] = 'common:validDatabaseCollection: Collection ' . $collectionName . ' is not authorized';
	return false;
} /* function validDatabaseCollection */

?>

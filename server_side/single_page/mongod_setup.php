<?php
/**
 * Find the database access configuration based on environment
 * Moved on top to load the MongoClient before defining __autoload
 */

/* production environment */
$mongoUrl = "mongodb://" . $_SESSION ['url_domain'] . ":27017";
$_SESSION ['database_name'] = "db1";

if ($_SERVER ['SERVER_NAME'] == 'localhost') {
	/* development environment */
	$mongoUrl = "mongodb://localhost:27017";
	$_SESSION ['database_name'] = "jpm_devlopment";
}

/**
 * Initializae the inbuild mongo client
 */
$mongoClient = new MongoClient ( $mongoUrl );

/**
 * Create the mongodb database/document instance
 */
$_SESSION ['mongo_database'] = $mongoClient->{$_SESSION ['database_name']};

/**
 * Read the organization detail from the database
 */
$_SESSION ['url_domain_org'] = $_SESSION ['mongo_database']->organization->findOne ( array (
		'web_domain.name' => $_SESSION ['url_domain']
) );

?>
<?php
/**
 * Find the database access configuration based on environment
 * Moved on top to load the MongoClient before defining __autoload
 */

/* production environment */
$mongoUrl = "mongodb://" . $_SESSION ['url_domain'] . ":27017";
$mongoUrl = "mongodb://localhost:27017"; /* on production box only localhost is listining */
$_SESSION ['database_name'] = "db1";

if ($_SESSION ['url_domain']  == 'localhost' 
		|| $_SESSION ['url_domain'] == 'admin.localhost'
		|| $_SESSION ['url_domain'] == 'service.localhost'
	) {
	/* development environment */
	$mongoUrl = "mongodb://localhost:27017";
	$_SESSION ['database_name'] = "jpm_devlopment";
	$_SESSION ['database_name'] = "db1";
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

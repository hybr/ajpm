<?php

/**
 * This is the application interface for rest
 * 
 * LICENSE: Owned by HYBR
 *
 * @category   HYBR
 * @package    JPM
 * @subpackage index
 * @copyright  Copyright (c) 2011-2016 HYBR Trust (http://www.hybr.in)
 * @license    Private
 * @version    $Id:$
 * @link       http://www.hybr.in
 * @since      File available since Release 1.0
 */

?>

<?php

/**
 * session_start() creates a session or resumes the current one based on a session
 * identifier passed via a GET or POST request, or passed via a cookie.
 */
session_start ();

/**
 * Constants to hold the absolute path of ajpm lib folder on server
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
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
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
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';


// Prevent caching.
header ( 'Cache-Control: no-cache, must-revalidate' );
/* header ( 'Expires: ' . date () ); */

// The JSON standard MIME header.
header ( 'Content-type: application/json' );

/* task to get organization record */
if ($_SESSION['url_action'] == 'public_CustomRequest' &&
	$_SESSION['url_task'] == 'urlDomainOrg'
) {
		echo json_encode ($_SESSION['url_domain_org']);

$recordsOwnedByOrg = array (
	'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
);

/* task to get home page web page record */
} else if ($_SESSION['url_action'] == 'public_WebPage'
			&& $_SESSION['url_task'] == 'presentjson'
			&& $_SESSION['url_sub_task'] == 'Home Page'
) {
	echo json_encode($_SESSION ['mongo_database']->web_page->findOne (array(
		'$and' => array (
			array ('web_page_type' => array('$elemMatch' => array('name' =>'home_page')),),
			array ('for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ))
		)
	)));
	
	
/* task to get about us web page record */
} else if ($_SESSION['url_action'] == 'public_WebPage'
		&& $_SESSION['url_task'] == 'presentjson'
		&& $_SESSION['url_sub_task'] == 'About Us'
) {
	echo json_encode($_SESSION ['mongo_database']->web_page->findOne (array(
		'$and' => array (
			array ('web_page_type' => array('$elemMatch' => array('name' =>'about_us')),),
			array ('for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] ))
		)
	)));

/* get rest of record based on classes defined */
} else {
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'content.php';
	echo $jpmContent;
} 

?>

<?php debugPrintArray($_SESSION, '$_SESSION'); ?>

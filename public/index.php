<?php

/**
 * This is the entry file for JPM framework.
 * A web framework based on jquery, php, mongodb
 * Adding AngularJS use from this release
 *
 * This framework is designed to host multiple websites from a single
 * location and their data in mongodb. These small websites could be
 * personal of commercial websites.
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
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'debug.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';


/**
 * This is main home page code file.
 * It has a mechanisum to create the home page based on domain name
 */

if ($_SESSION['request_type'] == 'service') {
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';	
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'content.php';
	echo $jpmContent;
} else if ($_SESSION['request_type'] == 'partial') {
	echo file_get_contents(SERVER_SIDE_PUBLIC_DIR . $urlPartsArray['path']);
} else {
	// echo "default " . $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR .  '_default.php';
	if(file_exists($_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR . '_default.php')) {
		include $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR .  '_default.php';
	}

	if ($_SERVER['SERVER_NAME'] == "ji.owebp.com") {
		echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-77504692-1', 'auto');ga('send', 'pageview');</script>";
	}
	
	if ($_SERVER['SERVER_NAME'] == "farm.hybr.in" || $_SERVER['SERVER_NAME'] == "farm.hybr.owebp.com") {
		echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-77614117-1', 'auto');ga('send', 'pageview');</script>";
	}

}

debugPrintArray($urlPartsArray, 'urlPartsArray');
debugPrintArray($personRbacRules, '$personRbacRules');
debugPrintArray($_SESSION, 'SESSION');



?>

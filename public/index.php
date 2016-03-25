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
 * Initialize the session array so every time we rebuild it
 */
$_SESSION = [];

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
echo $_SESSION ['allowed_as'];
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'content.php';

/**
 * This is main home page code file.
 * It has a mechanisum to create the home page based on domain name
 */

if ($_SESSION['request_type'] == 'service') {
	echo $jpmContent;
} else {
	if(file_exists($_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR . '_default.php')) {
		include $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR .  '_default.php';
	}
}
debugPrintArray($_SESSION, 'SESSION');
?>


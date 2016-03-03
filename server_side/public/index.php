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
 * Include the common files
 */
include dirname(__FILE__) . '/../single_page/common.php';
include dirname(__FILE__) . '/../single_page/org_details.php';
include dirname(__FILE__) . '/../single_page/mongod_setup.php';
include dirname(__FILE__) . '/../single_page/autoload.php';
include dirname(__FILE__) . '/../single_page/parse_action.php';
include dirname(__FILE__) . '/../single_page/get_menu.php';
include dirname(__FILE__) . '/../single_page/permission.php';
include dirname(__FILE__) . '/../single_page/query_condition.php';
include dirname(__FILE__) . '/../single_page/content.php'; 

if ($_SESSION['debug']) {
	echo '<pre>'; print_r($_SESSION); echo '</pre>';
}

?>
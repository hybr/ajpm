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
	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'backend'
 	. DIRECTORY_SEPARATOR . 'php'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
);

/**
 * Include the common files
 */
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'debug.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'common.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'url_domain.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'layout_and_theme.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'mongod_setup.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'action_and_task.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'autoload.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';
include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'content.php';

?>

<html>
	<head>
		<!-- jQuery lib -->
		<script src="/js/jquery/jquery-ui/external/jquery/jquery.js"></script>
		<script src="/js/jquery/jquery-ui/jquery-ui.min.js"></script>
		<link media="all" type="text/css" rel="stylesheet" href="/js/jquery/jquery-ui-themes/themes/<?php echo isset($_SESSION ['url_domain_org'] ['web_site_theme'])?$_SESSION ['url_domain_org'] ['web_site_theme']:'cupertino'; ?>/jquery-ui.min.css" />
		
		<link rel="stylesheet" href="/js/jstree/themes/default/style.min.css" />
		<script src="/js/jstree/jstree.min.js"></script>
		
		<!-- application -->
		<link rel="stylesheet" type="text/css" 	href="/css/owebp/common.css">
		<link rel="stylesheet" type="text/css" 	href="/css/owebp/admin.css">
		<script src="/js/owebp/common.js"></script>
		<script src="/js/owebp/admin.js"></script>	
		
		<title><?php
			/* admin UI title does not have angular metatag variable as it is in jquery */
			echo $_SESSION ['url_domain_org']['abbreviation'];
			echo getTitle($_SESSION ['url_action']) . ' ' . getTitle($_SESSION['url_task']);
		?></title>
	</head>
	<body>
		<?php 
		/**
		 * First div to show logo, organization name, organization statement
		 */

		?>
		
		<div class="ui-widget">
		<div class="ui-widget-content ui-corner-all" style="padding-left: 15px;">
			<h1><?php
		
			if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
				echo '<img src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" style="width:70px; float: left;" />&nbsp;';
			}
			if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
				echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
			} else {
				echo "OWebP";
			}
			?></h1><h3><?php
			if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
				echo $_SESSION ['url_domain_org'] ['statement'];
			} else {
				echo "Best Presence on Web";
			}
			?></h3>
		</div></div>
		
		<?php 
		/**
		 * To show the navigation bar
		 */
		?>
		<div class="ui-widget">
		<div class="ui-widget-header ui-corner-all jpmHeaderPadding">
			<?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { ?>
				<div style="width:100px;float:left;" >
				<ul id="jpm_home_page_menu" class="ui-corner-all jpmContentPadding" >
					<li>Start<?php echo getMenu();?></li>
				</ul>
				</div>
			<?php } ?>
		
			<?php if (isset($_SESSION['url_domain_org']['web_site_content_type']) 
				&&  $_SESSION['url_domain_org']['web_site_content_type'] == 'Family Tree'
			) { ?>
				<a title="Family Tree" href="/family_tree/present_all">Family Tree</a>
			<?php } else { ?>
				<a title="Our Products and Services" href="/item_catalog/present_all">Catalog</a>
				<a title="Items seleced for purchase" href="/shopping_cart/present_all">Cart</a>
			<?php } ?>
		
			<?php if ($_SESSION ['allowed_as'] == 'PUBLIC' || $_SESSION ['allowed_as'] == 'NULL') {
				echo '<a title="'. $_SESSION['allowed_as'] . '" href="/user/login">Login</a>';
			} else {
				echo '<a title="'. $_SESSION['allowed_as'] . '" href="/user/logout">Logout</a>';
			} ?>
		
			<a title="Join and manage you business" href="/user/join">Join</a>
		
			<?php
			$_SESSION ['login_person_id'] = '';
			if (isset($_SESSION['person']) && isset($_SESSION['person']['_id'])) {
				$_SESSION ['login_person_id'] = ( string ) $_SESSION ['person'] ['_id'];
			}
			if ($_SESSION ['allowed_as'] != 'PUBLIC' && $_SESSION ['allowed_as'] != 'NULL') {
				$personName = '';
				if ($_SESSION['login_person_id'] == '') { 
					/* person account is not set */
				} else {
					$personClass =  new public_Person();
					$personClass->record = $_SESSION ['person'];
					$personName = $personClass->getFullName('Official');
				}
				echo '<a href="/user/update?id='.(string)$_SESSION ['user']['_id'] 
					.'">Welcome</a>';
				if ($personName != '') {
					echo ' <a href="/person/update?id='.$_SESSION ['login_person_id']
						.'">'.$personClass->getFullName('Official').'</a>';
				}
			}
			?>		
			<a href="/contact/present_all">Contact Us</a>
		</div></div><br />


		<?php 
		/**
		 * To show the page content
		 */
		echo $jpmContent; 
		?>
		
		<?php 
		if ($_SERVER['SERVER_NAME'] == "ji.owebp.com" || $_SERVER['SERVER_NAME'] == "ji2.owebp.com"
			|| $_SERVER['SERVER_NAME'] == "admin.jaipurinvestor.com") {
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
		?>
		
		<?php debugPrintArray($_SESSION, '$_SESSION'); ?>
	</body>
</html>

<?php

/**
 * This is the setup guide for the website
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
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'backend'
 	. DIRECTORY_SEPARATOR . 'php'
);

define ( 'SERVER_SIDE_SP_DIR', SERVER_SIDE_PUBLIC_DIR
	. DIRECTORY_SEPARATOR . '..'
 	. DIRECTORY_SEPARATOR . 'interfaces'
 	. DIRECTORY_SEPARATOR . 'frontend'
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

?>

<?php debugPrintArray($_SESSION, '$_SESSION'); ?>

<h1>Setup Guide for <?php echo $_SESSION['url_domain'] ?> Website</h1>

This guide will help you to configure and setup <b><?php echo $_SESSION['url_domain'] ?></b>.

<h2>Notes</h2>Information about the website
<ul>
	<li>Each website has two main pages
		<ul>
			<li><a style="color: red;" target="_blank" href="http://<?php echo $_SESSION['url_domain'] ?>">Main Page</a>: This is for public to see your website.</li>
			<li><a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>">Admin Page</a>: This is for website owner to setup and configure the <b><?php echo $_SESSION['url_domain'] ?></b> website.</li>
		</ul>	
	</li>
</ul>

<h2>Steps</h2>to setup and configure the website. <b>Read each step carefully before performing the task</b>
<ol>
	<li><b>Create your login credential</b>
		<ol>
			<li>You need to visit registration page</li>
			<li>When you visit registration page provide your email address and password to join website</li>
			<li>When you complete this come back on the setup guide for next task</li>
			<li>Click here to go to <a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>/user/join">Registration Page</a></li>
		</ol>
	</li>
	<li><b>Login to your account</b>
		<ol>
			<li>You need to visit login page</li>
			<li>When you visit login page provide your email address and password to login website</li>
			<li>When you complete this come back on the setup guide for next task</li>
			<li>Click here to go to <a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>/user/login">Login Page</a></li>
		</ol>
	</li>
	<li><b>Create personal profile</b>
		<ol>
			<li>There are two records which user posses
				<ol>
					<li><u>Login Credentials</u>: A user can have one or more login credentials (login record). Login credential helps user to login to website. Each login credential holds information of email address and password.</li>
					<li><u>Personal Profile</u>: A user has one personal profile (person record). Personal profile holds information about person like name, gender, login credentials, contacts, photos, relation with other persons, etc</li> 
				</ol>
			</li>
			<li>For your login account to work the login record and person record needs to be linked both side.</li>


			<li>You need to visit person create page first and then login credential update page
				<ol>
					<li><b>Create person record</b>
						<ol>
							<li>When you login without the person record created you will see this message <u>You are logged in. | - Please create person profile. Assign it to your credentials and re-login.</u></li>
							<li>When you visit person create page provide minimum your name and login records your are using</li>
							<li>After login you see a message. You can click on person button there and then create button to create personal profile</li>
							<li>or Click here to go to <a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>/person/create">Person Record Create Page</a></li>
						</ol>
					</li>
					<li><b>Associate person record with login record</b>
						<ol>
							<li>Once person record is created it needs to be linked with login record</li>
							<li>When you reach update page go to person field and start typing the name you used in person record. Select the appropriate record from dropdown list and then click on submit to save the changes.</li>
							<li>Click here to go to <a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>/user">Login Record List Page</a> and then click on Update button.</li>
						</ol>
					</li>
				</ol>
			</li>
			<li>When person record is created assign it also to login credential. For your login account to work the login record and person record needs to be linked both side.</li>
			<li>When you complete this come back on the setup guide for next task</li>
		</ol>
	</li>
	<li><b>Authorizing person to perform tasks in start menu</b>
		<ol>
			<li>When a user is logged in with person record created and linked with login record, then user can only update records in <i style="color: blue;">Start -> Person</i> menu. For rest of the authorizations website owner needs to assign the access rights to you.</li>
			<li>If you are website owner then talk to support team at owebp.com at +91 800 349 2766 and they will assign you as owner of website</li>
		</ol>
	</li>
	<li><b>Create organization record</b>
		<ol>
			<li>If your account is authorized as website owner then you can create the organizatoin record</li>
			<li>Click here to go to <a style="color: red;" target="_blank" href="http://admin.<?php echo $_SESSION['url_domain'] ?>/organization">Organization Record List Page</a> and then click on Update button.</li>
		</ol>
	</li>
</ol>

<?php
/**
 * read the record for current domain in url
 */
$_SESSION ['url_domain'] = $_SERVER ['SERVER_NAME'];

/**
 * if it is local host then owebp.com is default domain
 * rest can be used to test those setup's for testing purpose only
 */
if ($_SESSION ['url_domain'] == 'localhost') {
	$_SESSION ['url_domain'] = 'eti.owebp.com';
	$_SESSION ['url_domain'] = 'hybr.owebp.com';
	$_SESSION ['url_domain'] = 'syspro.owebp.com';
	$_SESSION ['url_domain'] = 'pkmishra.owebp.com';
	$_SESSION ['url_domain'] = 'owebp.com';
}

/**
 * Remove www string from the front of the domain name
 */
$_SESSION ['url_domain'] = preg_replace ( '/^www\./i', '', $_SESSION ['url_domain'] );

?>
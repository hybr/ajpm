<?php
/**
 * read the record for current domain in url
 */
if (!isset($_SESSION ['url_domain'])) {
	$_SESSION ['url_domain'] = $_SERVER ['SERVER_NAME'];
}

/**
 * if it is local host then owebp.com is default domain
 * rest can be used to test those setup's for testing purpose only
 */
if (isset ( $_GET ['lhdn'] )) { /* lhdn = local host domain name */
	$_SESSION ['url_domain']= $_GET ['lhdn'];
}

/**
 * Remove www string from the front of the domain name
 */
$_SESSION ['url_domain'] = preg_replace ( '/^www\./i', '', $_SESSION ['url_domain'] );

$_SESSION['view_type'] = '';
if ($_SESSION['url_domain'] == 'ji.owebp.com') {
	$_SESSION['view_type'] = '_bs';
}
?>
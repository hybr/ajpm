<?php

$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout';

/* providing a way to add extra home pages for the different domains
 * if home page is not created for the domain then the default home page
 * of owebp.com will be used
*/
$_SESSION ['UI_FOLDER'] = $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR . 'owebp.com';

if (file_exists($_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR  . $_SESSION['url_domain'])) {
	$_SESSION ['UI_FOLDER'] = $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR . $_SESSION['url_domain'];
}

$_SESSION['view_type'] = '';
if ($_SESSION['url_domain'] == 'ji.owebp.com') {
	$_SESSION['view_type'] = '_bs';
}

?>
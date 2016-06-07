<?php

$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/web';
$_SESSION['request_type'] = 'web';

$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );


if (array_key_exists ( 'path', $urlPartsArray )) {
	$urlPathArray = split ( '/', $urlPartsArray ['path'] );
	
	if (sizeof ( $urlPathArray ) >= 2) {
		foreach ( split ( '_', $urlPathArray [1] ) as $w ) {
			if (strpos($w, '-a-') !== FALSE) {
				$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/admin';
				$_SESSION['request_type'] = 'admin';
				$w = str_replace('-a-', '', $w);
			}
			if (strpos($w, '-s-') !== FALSE) {
				$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/service';
				$_SESSION['request_type'] = 'service';
				$w = str_replace('-s-', '', $w);
			}
			$_SESSION ['url_action'] .= ucfirst ( strtolower ( $w ) );
			$_SESSION['url_collection'] .=  '_' . strtolower($w);
		}
	}
	if (preg_match('/\.html/', $urlPartsArray ['path'], $m)) {
		$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/service';
		$_SESSION['request_type'] = 'partial';
	}
}

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
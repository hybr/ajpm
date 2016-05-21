<?php
/**
 * find action/module/class/component, task, subtask requested
 * based on action a class is loaded
 * based on task/sub task the method in class is executed
 */

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

debugPrintArray($urlPartsArray, 'urlPartsArray');

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

/* find action */
$_SESSION ['url_action'] = 'public_';
$_SESSION ['url_task'] = '';
$_SESSION ['url_sub_task'] = '';
$_SESSION['url_collection'] = '';
/* identify if it is a web_ui, admin_ui or service_ui */
$_SESSION['request_type'] = 'web';
$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/web';


if (array_key_exists ( 'path', $urlPartsArray )) {
	$urlPathArray = split ( '/', $urlPartsArray ['path'] );
	/* array_shift will remove word service, word service is used for backend calls */
	debugPrintArray($urlPathArray, 'urlPathArray');
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
		$_SESSION['url_collection'] = preg_replace('/^_/', '', $_SESSION['url_collection']);
	}
	if (sizeof ( $urlPathArray ) >= 3) {
		foreach ( split ( '_', $urlPathArray [2] ) as $w ) {
			$_SESSION ['url_task'] .= ucfirst ( strtolower ( $w ) );
		}
	}
	if (sizeof ( $urlPathArray ) >= 4) {
		$urlPathArray [3] = str_replace ( "%20", '_', $urlPathArray [3] );
		$urlPathArray [3] = str_replace ( " ", '_', $urlPathArray [3] );
		foreach ( split ( '_', $urlPathArray [3] ) as $w ) {
			$_SESSION ['url_sub_task'] .= ucfirst ( strtolower ( $w ) ) . ' ';
		}
	}
	if (preg_match('/\.html/', $urlPartsArray ['path'], $m)) {
		$_SESSION ['LAYOUT_DIR'] = SERVER_SIDE_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'layout/service';
		$_SESSION['request_type'] = 'partial';
	}
}
if ($_SESSION ['url_action'] == 'public_') {
	/* Home Page */
	$_SESSION ['url_action'] = 'public_WebPage';
	$_SESSION ['url_task'] = 'present';
	$_SESSION['url_collection'] = 'web_page';
	/* if organization record exists then read the home page id */
	if (array_key_exists ( 'url_domain_org', $_SESSION ) && $_SESSION ['url_domain_org']) {
		$urlArgsArray ['id'] = $_SESSION ['url_domain_org'] ['web_site_home_page'];
	}
}
if ($_SESSION ['url_task'] == '') {
	$_SESSION ['url_task'] = 'presentAll';
	if (isset ( $_SESSION ['user'] ) && ! empty ( $_SESSION ['user'] )) {
		$_SESSION ['url_task'] = 'read';
	}
}
$_SESSION ['url_task'] = lcfirst ( $_SESSION ['url_task'] );

if ($_SESSION ['url_sub_task'] == '') {
	$_SESSION ['url_sub_task'] = 'All';
}
$_SESSION ['url_sub_task'] = trim ( $_SESSION ['url_sub_task'] );

debugPrintArray($_SESSION, 'SESSION');
?>

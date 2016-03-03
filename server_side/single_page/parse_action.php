<?php
/**
 * find action/module/class/component, task, subtask requested
 * based on action a class is loaded
 * based on task/sub task the method in class is executed
 */

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

/* find action */
$_SESSION ['url_action'] = 'public_';
$_SESSION ['url_task'] = '';
$_SESSION ['url_sub_task'] = '';
if (array_key_exists ( 'path', $urlPartsArray )) {
	$urlPathArray = split ( '/', $urlPartsArray ['path'] );
	if (sizeof ( $urlPathArray ) >= 2) {
		foreach ( split ( '_', $urlPathArray [1] ) as $w ) {
			$_SESSION ['url_action'] .= ucfirst ( strtolower ( $w ) );
		}
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
}
if ($_SESSION ['url_action'] == 'public_') {
	/* Home Page */
	$_SESSION ['url_action'] = 'public_WebPage';
	$_SESSION ['url_task'] = 'present';
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

?>
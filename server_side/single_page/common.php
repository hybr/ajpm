<?php

/**
 * A constant to hold the absolute path of ajpm lib folder on server
 *
 * @constant string OWEBP_DIR
 */
define ( 'AJPM_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..');
define ( 'OWEBP_DIR', AJPM_DIR
. DIRECTORY_SEPARATOR . 'lib'
		. DIRECTORY_SEPARATOR . 'owebp'
);

/* debug option */
if (isset ( $_GET ['debug'] )) {
	$_SESSION ['debug'] = true;
} else {
	$_SESSION ['debug'] = false;
}

/**
 * Convert a string in to title format
 * @param string $title string to be converted
 * @return string converted string in title format
 */
function getTitle($title) {
	$titleWordArray = split ( '_', $title );
	$returnString = '';
	foreach ( $titleWordArray as $titleWord ) {
		$returnString .= ' ' . ucfirst ( strtolower ( $titleWord ) );
	}
	return $returnString;
}

?>
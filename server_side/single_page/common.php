<?php

/**
 * A constant to hold the absolute path of ajpm lib folder on server
 *
 * @constant string DIR
 */
define ( 'AJPM_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..');
define ( 'DIR', AJPM_DIR
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

function debugPrintArray($a, $msg = '') {
	if (!$_SESSION['debug']) return;
	echo '<hr />';
	echo 'DEBUG of '. $msg .'<pre>';
	$traces = debug_backtrace();
	foreach($traces as $trace) {
		echo "<br />called by {$trace['class']} :: {$trace['function']}";
	}
	echo '<hr />';
			print_r ( $a );
			echo '</pre>';
			echo '<hr />';
}
?>
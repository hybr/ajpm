<?php

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
	/*
	 * 
	$traces = debug_backtrace();
	foreach($traces as $trace) {
		echo "<br />called by {$trace['class']} :: {$trace['function']}";
	}
	*/
	echo '<hr />';
			print_r ( $a );
			echo '</pre>';
			echo '<hr />';
}

function getParamValue($key, $args) {
	if (isset($_POST [$key])) return $_POST [$key];
	if (isset($_GET [$key])) return $_GET [$key];
	if (isset($args [$key])) return $args [$key];
	return null;
}
?>

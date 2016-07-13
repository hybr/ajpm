<?php
/* debug option */
if (isset ( $_GET ['debug'] )) {
	$_SESSION ['debug'] = true;
	header('Content-Type: text/html');
	echo '<!DOCTYPE html>';
} else {
	$_SESSION ['debug'] = false;
}

function debugPrintArray($a, $msg = '') {
	if (!$_SESSION['debug']) return;
	echo "\n\n<br />=============================================================================================<br />\n\n";
	echo '<hr />';
	echo 'DEBUG of '. $msg;
	echo '<hr />' . "\n\n";
	echo '<pre>';
	print_r ( $a );
	echo '</pre>';
	echo '<hr />' . "\n\n";
	$traces = debug_backtrace();
	foreach($traces as $trace) {
		echo "\n\n<br /> $msg called by {";
		if (array_key_exists('class', $trace)) echo $trace['class'];
		echo "} :: {";
		if (array_key_exists('function', $trace)) echo $trace['function'];
		echo "}";
	}
	echo "\n\n<br/>---------------------------------------------------------------------------------------------<br />\n\n";
}

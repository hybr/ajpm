<?php
/* debug option */
if (isset ( $_GET ['debug'] )) {
	$_SESSION ['debug'] = true;
} else {
	$_SESSION ['debug'] = false;
}

function debugPrintArray($a, $msg = '') {
	if (!$_SESSION['debug']) return;
	echo '<hr />';
	echo 'DEBUG of '. $msg .'<pre>';
	/*
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
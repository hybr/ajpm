<html>
	<head>
		<?php if(file_exists( $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR  . '_head.php')) 
			include  $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_head.php'; 
		?>
	</head>
	<body>
		<?php if(file_exists( $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_body.php')) 
			include  $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . '_body.php';
		?>
	</body>
</html>


/**
 * This is main home page code file.
 * It has a mechanisum to create the home page based on domain name
 */

if ($_SESSION['request_type'] == 'service') {
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'get_menu.php';
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'permission.php';
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'query_condition.php';	
	include SERVER_SIDE_SP_DIR . DIRECTORY_SEPARATOR . 'content.php';
	echo $jpmContent;
} else if ($_SESSION['request_type'] == 'partial') {
	echo file_get_contents(SERVER_SIDE_PUBLIC_DIR . $urlPartsArray['path']);
} else {
	// echo "default " . $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR .  '_default.php';
	if(file_exists($_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR . '_default.php')) {
		include $_SESSION ['LAYOUT_DIR'] . DIRECTORY_SEPARATOR .  '_default.php';
	}



}

debugPrintArray($urlPartsArray, 'urlPartsArray');
debugPrintArray($_SESSION, 'SESSION');
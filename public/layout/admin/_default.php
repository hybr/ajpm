<html>
	<?php 
	/* providing a way to add extra home pages for the different domains
	 * if home page is not created for the domain then the default home page 
	 * of owebp.com will be used 
	 */
	$homePageDir = dirname(__FILE__) . DIRECTORY_SEPARATOR  . 'owebp.com';
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $_SESSION['url_domain'])) {
		$homePageDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $_SESSION['url_domain'];
	}
	?>
	<head>
		<?php if(file_exists($homePageDir . DIRECTORY_SEPARATOR  . '_head.php')) 
			include $homePageDir . DIRECTORY_SEPARATOR . '_head.php'; 
		?>
	</head>
	<body>
		<?php if(file_exists($homePageDir . DIRECTORY_SEPARATOR . '_body.php')) 
			include $homePageDir . DIRECTORY_SEPARATOR . '_body.php';
		?>
	</body>
</html>
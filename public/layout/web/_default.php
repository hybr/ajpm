<!doctype html>
<!--[if lt IE 7]>	<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>	<html class="no-js"> <![endif]-->

<html ng-app="ajpmApp">
	<?php 
	/* providing a way to add extra home pages for the different domains
	 * if home page is not created for the domain then the default home page 
	 * of owebp.com will be used 
	 */
	$homePageDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'owebp.com';
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $_SESSION['url_domain'])) {
		$homePageDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $_SESSION['url_domain'];
	}
	?>
	<head>
		<?php if(file_exists($homePageDir . DIRECTORY_SEPARATOR . '_head.php')) 
			include $homePageDir . DIRECTORY_SEPARATOR . '_head.php'; 
		?>
	</head>
	<body>
		<script>var _hpid = '<?php echo (string) $_SESSION ['url_domain_org']['web_site_home_page']; ?>';</script>
		<script>var _orgStatement = '<?php echo (string) $_SESSION ['url_domain_org']['statement']; ?>';</script>
		<script>var _auid = '<?php echo (string) $_SESSION ['url_domain_org']['web_site_about_us_page']; ?>';</script>
		<?php if(file_exists($homePageDir . DIRECTORY_SEPARATOR . '_body.php')) 
			include $homePageDir . DIRECTORY_SEPARATOR . '_body.php';
		?>
	</body>
</html>
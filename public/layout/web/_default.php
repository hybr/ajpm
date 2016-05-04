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
	<body ng-cloak ng-controller="ApplicationController">
		<script>var _hpid = '<?php 
			if (isset($_SESSION ['url_domain_org']['web_site_home_page'])) {
				echo (string) $_SESSION ['url_domain_org']['web_site_home_page'];
			} else {
				echo '';
			}
			 
		?>';</script>
		<script>var _orgStatement = '<?php 
			if (isset($_SESSION ['url_domain_org']['statement'])) {
				echo (string) $_SESSION ['url_domain_org']['statement']; 
			} else {
				echo "We provide best products and services";
			}
		?>';</script>
		<script>var _auid = '<?php 
			if (isset($_SESSION ['url_domain_org']['web_site_about_us_page'])) {
				echo (string) $_SESSION ['url_domain_org']['web_site_about_us_page'];
			} else {
				/* if no about us page is setup use home page */
				echo (string) $_SESSION ['url_domain_org']['web_site_home_page'];
			}
		?>';</script>
		<script>var _theme_2 = '<?php 
			if (isset($_SESSION ['url_domain_org']['web_site_theme_2'])) {
				echo (string) $_SESSION ['url_domain_org']['web_site_theme_2']; 
			} else {
				echo "brown_grey_orange_lime_";
			}
		?>';</script>
		<script>var _org_id = '<?php 
			if (isset($_SESSION ['url_domain_org']['_id'])) {
				echo (string) $_SESSION ['url_domain_org']['_id']; 
			} else {
				echo "";
			}
		?>';</script>
		<?php if(file_exists($homePageDir . DIRECTORY_SEPARATOR . '_body.php')) 
			if ($_SESSION ['url_domain'] == 'ji2.owebp.com') {
				include $homePageDir . DIRECTORY_SEPARATOR . '_body.php';
			} else {
				include $homePageDir . DIRECTORY_SEPARATOR . '_bs_body.php';
			}
		?>
	</body>
</html>

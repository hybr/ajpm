<html ng-app="ajpmApp">
	<head>
		<?php if(file_exists($_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . $_SESSION['view_type'] . '_head.php')) 
			// 	echo ' head ' . $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . $_SESSION['view_type'] . '_head.php';
			include $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . $_SESSION['view_type'] . '_head.php'; 
		?>
	</head>
	
	<body ng-cloak ng-controller="ApplicationController">
		<script>var _lhdn = '<?php 
			if (isset($_SESSION ['url_domain'])) {
				echo (string) $_SESSION ['url_domain']; 
			} else {
				echo "owebp.com";
			}
		?>';</script>	
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
		<?php if(file_exists($_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . $_SESSION['view_type'] .'_body.php')) 
			include $_SESSION ['UI_FOLDER'] . DIRECTORY_SEPARATOR . $_SESSION['view_type'] . '_body.php';
		?>
	</body>
</html>

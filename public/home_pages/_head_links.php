<meta charset="utf-8">
<meta name="description" content="">
<meta name="viewport" content="width=device-width">

<!-- angular lib -->
<script src="js/angular/angular.min.js"></script>
<script src="js/angular/angular-ui-router.min.js"></script>

<!-- jQuery lib -->
<script src="js/jquery/jquery-ui/external/jquery/jquery.js"></script>
<script src="js/jquery/jquery-ui/jquery-ui.min.js"></script>
<link media="all" type="text/css" rel="stylesheet"
	href="js/jquery/jquery-ui-themes/themes/<?php echo isset($_SESSION ['url_domain_org'] ['web_site_theme'])?$_SESSION ['url_domain_org'] ['web_site_theme']:'cupertino'; ?>/jquery-ui.min.css" />

<!-- application common css -->
<link rel="stylesheet" type="text/css" 	href="css/app.css">
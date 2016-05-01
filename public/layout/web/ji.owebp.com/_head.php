
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php
	/* the default title is Organization is based on organization detail and 
	 * page requested
	 * - First word is organization abbriviation
	 * - Second word is name of module/class/collection name. All these three 
	 *   will be almost same word.
	 * - Third word will be the task inside the module
	 */
	echo $_SESSION ['url_domain_org']['abbreviation'];

	if ($actionInstance->collectionName == 'web_page' 
			&& $_SESSION['url_task'] == 'present') { 
		echo ''; /* web page title will be get added via javascript later */ 
	} else {
		echo getTitle($actionInstance->collectionName) . ' ' 
				. getTitle($_SESSION['url_task']);
	}
?></title>

<!--   <link rel="stylesheet" href="js/angular/angular-material.min.css"> -->
 
<!-- jQuery lib -->
<script src="/js/jquery/jquery-ui/external/jquery/jquery.js"></script>
<link media="all" type="text/css" rel="stylesheet" href="/js/jquery/jquery-ui-themes/themes/<?php echo isset($_SESSION ['url_domain_org'] ['web_site_theme'])?$_SESSION ['url_domain_org'] ['web_site_theme']:'cupertino'; ?>/jquery-ui.min.css" />
        
<!-- Bootstrap -->
<script src="/js/bootstrap/bootstrap.min.js"></script>
<link href="/js/bootstrap/bootstrap.css" rel="stylesheet">

<link href="layout/web/ji.owebp.com/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/icon.css">
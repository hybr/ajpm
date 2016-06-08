<meta charset="utf-8">

<title>

<?php
	/* the default title is Organization is based on organization detail and 
	 * page requested
	 * - First word is organization abbriviation
	 * - Second word is name of module/class/collection name. All these three 
	 *   will be almost same word.
	 * - Third word will be the task inside the module
	 */
	echo $_SESSION ['url_domain_org']['abbreviation'];
?> {{ metaTags.title }} <?php 
	echo getTitle($_SESSION ['url_action']) . ' ' . getTitle($_SESSION['url_task']);
?>

</title>

<meta name="description" content="{{ metaTags.description }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="js/angular/angular-material.min.css">
<link rel="stylesheet" type="text/css" href="css/icon.css">

<!-- slider -->
<link rel="stylesheet" type="text/css" 	href="js/slider/angular-carousel.css">

<!-- application -->
<link rel="stylesheet" type="text/css" 	href="css/owebp/common.css">
<link rel="stylesheet" type="text/css" 	href="css/owebp/web.css">


<link rel="stylesheet" type="text/css" 	href="css/owebp/common.css">
<link rel="stylesheet" type="text/css" 	href="css/owebp/web.css">
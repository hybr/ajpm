<title><?php
	/* admin UI title does not have angular metatag variable as it is in jquery */
	echo $_SESSION ['url_domain_org']['abbreviation'];
	echo getTitle($_SESSION ['url_action']) . ' ' . getTitle($_SESSION['url_task']);
?></title>


<?php if(file_exists(dirname(__FILE__) . '/../_head_links.php')) 
	include dirname(__FILE__) . '/../_head_links.php'; 
?>
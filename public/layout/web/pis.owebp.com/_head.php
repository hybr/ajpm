
<title><?php
	/* the default title is Organization is based on organization detail and 
	 * page requested
	 * - First word is organization abbriviation
	 * - Second word is name of module/class/collection name. All these three 
	 *   will be almost same word.
	 * - Third word will be the task inside the module
	 */
	echo $_SESSION ['url_domain_org']['abbreviation'];

	if ($_SESSION ['url_action'] == 'public_WebPage' 
			&& $_SESSION['url_task'] == 'present') { 
		echo ''; /* web page title will be get added via javascript later */ 
	} else {
		echo getTitle($_SESSION ['url_action']) . ' ' 
				. getTitle($_SESSION['url_task']);
	}
?></title>

<?php if(file_exists(dirname(__FILE__) . '/../_head_links.php')) {
	include dirname(__FILE__) . '/../_head_links.php'; 
} ?>

<style>
	v { border: 2px solid red; }
</style>

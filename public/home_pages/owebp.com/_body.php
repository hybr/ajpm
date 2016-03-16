<base href="/">
<div ng-controller="ApplicationController" class="ui-widget">

	<!-- First div to show logo, organization name, organization statement -->
	<div class="ui-widget-content ui-corner-all" style="padding-left: 15px;">
		<h1><?php
			if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
				echo '<img src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" style="width:70px; float: left;" />&nbsp;';
			}
			if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
				echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
			} else {
				echo "OWebP";
			}
		?></h1><h3><?php
			if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
				echo $_SESSION ['url_domain_org'] ['statement'];
			} else {
				echo "Best Presence on Web";
			}
		?></h3>
	</div>
	
	<!-- Top menu -->
	<form name="form_topMenu">
		<fieldset id="toolbar" class="ui-widget-header ui-corner-all">
			<legend></legend>
			
			<div id="topMenu">
			
				<?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { ?>
					<div style="width:100px;float:left;" >
					<ul id="ajpm_home_page_menu" class="ui-corner-all jpmContentPadding" >
						<li>Start<?php echo getMenu();?></li>
					</ul>
					</div>
				<?php } ?>
				
				<input type="radio" id="id_home" name="name_topMenuRadio" ui-sref="home">
				<label for="id_home">Home</label> 
				
				<input type="radio" id="id_about_us"
					name="name_topMenuRadio" ui-sref="about_us"> 
				<label for="id_about_us">About
					Us</label> 
				
				<input type="radio" id="id_contact_us" name="name_topMenuRadio"
					ui-sref="contact_us"> 
				<label for="id_contact_us">Contact
					Us</label>
					
				<span ng-if="isAuthenticated"><input type="radio" id="id_my_account" name="name_topMenuRadio"
					ui-sref="my_account"> 
				<label for="id_my_account"><span>{{ currentUserEmail }}</span></label></span>
				
				<span ng-if="isAuthenticated"><input type="radio" id="id_logout" name="name_topMenuRadio"
					ui-sref="logout"> 
				<label for="id_logout"><span>Logout</span></label></span>
	
				<input type="radio" id="id_admin_dashboard" name="name_topMenuRadio"
					ui-sref="admin_dashboard"> 
				<label for="id_admin_dashboard">Admin</label>				
			</div>
		</fieldset>
	</form>
	
	
	<!-- main message bar -->
	<div ng-show="hasPageMessages()"><ul>
		<li class="ui-state-error" ng-repeat="pageMessage in getPageMessages()">
			{{pageMessage}}
		</li>
	</ul></div>
	
	<div login-dialog-one id="userLoginModelOne" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div>
	<div login-dialog-two id="userLoginModelTwo" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div> 
	<div ui-view></div>
	
	
	<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php')) 
		include dirname(__FILE__) . '/../_body_links.php'; 
	?>
	
</div>
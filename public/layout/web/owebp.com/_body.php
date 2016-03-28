<base href="/">
<div ng-controller="ApplicationController">

<div layout="column" layout-fill>
  <md-toolbar class="md-tall">
    <div class="md-toolbar-tools">
    	<div>
	    	<?php
				if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
					echo '<img src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" style="width:70px; float: left;" />&nbsp;';
				}
			?>
	    	<b><?php if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
					echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
				} else {
					echo "OWebP";
			}?></b>
		</div>
		
       <md-button class="md-button" aria-label="Home" ui-sref="home">
          <span>Home</span>
       </md-button>
        
       <md-button class="md-button" aria-label="About" ui-sref="about_us">
          <span>About</span>
       </md-button>
               
      	<span flex></span>
		
		<md-button class="md-button" aria-label="Contact" ui-sref="contact_us">
          <span>Contact</span>
		</md-button>
		
		<md-button class="md-button" aria-label="My" ui-sref="my_account">
          <span>My</span>
		</md-button>		
  
  		<span ng-show="isAuthenticated">
			<md-button class="md-button" aria-label="Logout" ui-sref="logout">
	          <span>Logout</span>
			</md-button>
		</span>
		
  		<span ng-hide="isAuthenticated">
			<md-button class="md-button" aria-label="Login" ui-sref="login1">
	          <span>Login</span>
			</md-button>
		</span>		

       </div>
       
       <div class="md-toolbar-tools"><?php
			if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
				echo $_SESSION ['url_domain_org'] ['statement'];
			} else {
				echo "Best Presence on Web";
			}
		?></div>
  </md-toolbar>
  
  <md-content>
	
	<!-- main message bar -->
	<div ng-show="hasPageMessages()"><ul>
		<li ng-repeat="pageMessage in getPageMessages()">
			{{pageMessage}}
		</li>
	</ul></div>
	  
   <div ui-view></div>
  </md-content>
  
</div>

	<div login-dialog-one id="userLoginModelOne" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div>
	<div login-dialog-two id="userLoginModelTwo" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div>
	

	
	<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php'))
		include dirname(__FILE__) . '/../_body_links.php';
	?>

</div>

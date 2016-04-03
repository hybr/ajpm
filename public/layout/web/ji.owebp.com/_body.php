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
      	<span flex></span>
		
		<md-button class="md-button" aria-label="Contact" ui-sref="contact_us">
          <span>Contact</span>
		</md-button>
		
		<md-button class="md-icon-button" aria-label="Favorite" ui-sref="my_account">
          <i class="material-icons">face</i>
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
  
  
<md-toolbar class="md-menu-toolbar">
    <div layout="row">
        <md-menu-bar>
          
          <md-menu>
            
            <button ng-click="$mdOpenMenu()">
              Home
            </button>
            
            <md-menu-content>
              
              <md-menu-item class="md-indent">
              	<md-icon md-svg-icon="img/icons/ic_home_black_48px.svg"></md-icon>
                <md-button ui-sref="home">
                  Home
                </md-button>
              </md-menu-item>

              <md-menu-divider></md-menu-divider>

              <md-menu-item class="md-indent">
                <md-button ui-sref="home">
                  Contact Us
                </md-button>
              </md-menu-item>

              <md-menu-item class="md-indent">
                <md-button ui-sref="login1">
                  Login
                </md-button>
              </md-menu-item>
                            
            </md-menu-content>
          </md-menu>

          <md-menu>
            
            <button ng-click="$mdOpenMenu()">
              About
            </button>
            
            <md-menu-content>
              
              <md-menu-item class="md-indent">
                <md-button ui-sref="about_us">
                  Why Us
                </md-button>
              </md-menu-item>

              <md-menu-divider></md-menu-divider>

              <md-menu-item class="md-indent">
                <md-button>
                  Our Projects
                </md-button>
              </md-menu-item>

            </md-menu-content>
          </md-menu>
          
          <md-menu>
            <button ng-click="$mdOpenMenu()">
              Catalog
            </button>
            
            <md-menu-content>
			<md-menu-item class="md-indent">
                	<md-button>
                  		Services
                	</md-button>
				</md-menu-item>
			</md-menu-content>
			</md-menu>

		</md-menu-bar>
    </div>
  </md-toolbar>
	<div ng-show="hasPageMessages()"><ul>
		<li ng-repeat="pageMessage in getPageMessages()">
			{{pageMessage}}
		</li>
	</ul></div>  
   	<div ui-view>Loading...</div>  
</div>

	<div login-dialog-one id="userLoginModelOne" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div>
	<div login-dialog-two id="userLoginModelTwo" title="User Login" ui-jq="dialog" ui-options="{autoOpen: false, modal: true}"></div>
	

	
	<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php'))
		include dirname(__FILE__) . '/../_body_links.php';
	?>

</div>

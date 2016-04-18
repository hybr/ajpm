<base href="/">

 <div layout="column"><!--  page -->
	<div layout="column"><!-- header -->
		<md-toolbar class="md-toolbar-tools">
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
		
			<md-button class="md-primary md-raised" aria-label="Contact" ui-sref-active="is-active" ui-sref="contact_us">
	          Contact
			</md-button>
	
			<md-button class="md-primary md-raised" aria-label="Account" ui-sref-active="is-active" ui-sref="my_account">
	          My
	        </md-button>	
	  
	  		<span ng-show="isAuthenticated"> 
				<md-button class="md-primary md-raised" aria-label="Logout" ui-sref-active="is-active" ui-sref="logout">
		          Logout
				</md-button>
			</span>
			
	  		<span ng-hide="isAuthenticated">
				<md-button class="md-primary md-raised" ng-click="show_s1($event)" ui-sref-active="is-active" ui-sref="login1" >
				  Login
				</md-button>			
			</span>	
		
		</md-toolbar>
		
		<div flex></div>
		
       	<md-toolbar class="md-toolbar-tools"><?php
			if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
				echo $_SESSION ['url_domain_org'] ['statement'];
			} else {
				echo "Best Presence on Web";
			}
		?></md-toolbar>
	</div><!--  header -->
	
	<div flex></div><!-- menu -->
	
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
                <md-button ui-sref-active="is-active" ui-sref="home">
                  Home
                </md-button>
              </md-menu-item>

              <md-menu-divider></md-menu-divider>

              <md-menu-item class="md-indent">
                <md-button ui-sref-active="is-active" ui-sref="contact_us">
                  Contact Us
                </md-button>
              </md-menu-item>

              <md-menu-item class="md-indent">
                <md-button ui-sref-active="is-active" ui-sref="login1">
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
                <md-button ui-sref-active="is-active" ui-sref="about_us">
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
                	<md-button ui-sref-active="is-active" ui-sref="item_catalog">
                  		Services
                	</md-button>
				</md-menu-item>
			</md-menu-content>
			</md-menu>

          	<md-menu>
            	<button ui-sref-active="is-active" ui-sref="search2">Search</button>
			</md-menu>

		</md-menu-bar>
	</div><!--  layout="row" -->
  </md-toolbar>
 
	<div flex></div><!-- main content -->
	
	<div ng-show="hasPageMessages()"><ul>
		<li ng-repeat="pageMessage in getPageMessages()">
			{{pageMessage}}
		</li>
	</ul></div>  
		
	<md-content ui-view>Loading...</md-content> 
		  
 </div><!--  layout="column" page -->

<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php'))
	include dirname(__FILE__) . '/../_body_links.php';
?>
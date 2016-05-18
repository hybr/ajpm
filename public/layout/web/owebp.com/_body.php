<base href="/">
<md-content layout-fill>

	<div id="ajpmMainRow" layout="column">

		<div flex id="ajpmHeaderAndMenu" layout="column">

			<div flex id="ajpmHeader" layout="row" layout-xs="column">

				<div id="ajpmLogo">

					<?php if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
						echo '<img style="height: 75px; width: auto;" ng-src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" />';
					}?>

				</div><!-- ajpmLogo -->
				 
				<div flex id="ajpmNameStatement" >

					&nbsp;

					<span id="ajpmName" style="font-size:4.5vw; font-weight:bolder;">

						<?php if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
							echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
						} else {
							echo '<a href="/">Our WEB Presence</a>';
						}?>

					</span><!-- ajpmName -->

					&nbsp; 

					<span id="ajpmStatement" style="font-size:3vw; font-weight:bold;">

						<?php if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
							echo $_SESSION ['url_domain_org'] ['statement'];
						} else {
							echo "Best Presence on Web";
						}?>

					</span><!-- ajpmStatement -->
					
				</div><!-- ajpmNameStatement -->

			</div><!-- ajpmHeader -->

			<div id="ajpmMenu" >
			
				<md-toolbar>
			                    
					<div class="md-toolbar-tools" ng-hide="showSearchBarDiv">
			
						<span id="ajpmAllSmallMobileIcons" hide-gt-xs show-xs><!-- small mobile menu -->
									          
								<md-menu>
      						          
									<md-icon aria-label="Menu" ng-click="$mdOpenMenu()">
                                		menu<md-tooltip>Menu</md-tooltip>
                            		</md-icon>

						            <md-menu-content >
						            
										<md-menu-item ui-sref-active="md-warn" ui-sref="home">
										<md-button aria-label="Home">
											<i class="material-icons">home</i> Home
										</md-button>
										</md-menu-item>

										<md-menu-item ui-sref-active="md-warn" ui-sref="about_us">
			                            <md-button aria-label="About Us">
											<i class="material-icons">business</i> About Us
			                            </md-button>
										</md-menu-item>
						
										<md-menu-item ui-sref-active="md-warn" ui-sref="item_catalog">
			                            <md-button aria-label="Catalog">
											<i class="material-icons">shop</i> Catalog
			                            </md-button>
										</md-menu-item>
						       			
										<md-menu-divider></md-menu-divider>
																				
										<md-menu-item ui-sref-active="md-warn" ui-sref="contact_us">
			                            <md-button aria-label="Contact Us">
			                            	<md-tooltip>Our Contact Information</md-tooltip>
											<i class="material-icons">contacts</i> Contact Us
			                            </md-button>
										</md-menu-item>
						
										<md-menu-item ng-show="isAuthenticated">
										<md-button aria-label="Logout" ui-sref-active="md-warn" ui-sref="logout" ng-click="isAuthenticated = false">
											<md-tooltip>User Logout</md-tooltip>
											<i class="material-icons">cancel</i> Logout
										</md-button>
										</md-menu-item>						            

										<md-menu-item ng-hide="isAuthenticated">
										<md-button aria-label="Login" ui-sref-active="md-warn" ui-sref="login1" >
											<md-tooltip>User Login</md-tooltip>
											<i class="material-icons">input</i> Login
										</md-button>
										</md-menu-item>

										<md-menu-item ng-show="isAuthenticated">
			                            <md-button aria-label="My" ui-sref-active="md-warn" ui-sref="my_account">
			                            	<md-tooltip>My Profile</md-tooltip>
											<i class="material-icons">person</i> My
			                            </md-button>
										</md-menu-item>
																	
						            </md-menu-content>
						            
						          </md-menu>
						          
	                            <md-icon aria-label="Search" hide-gt-sm ng-click="showSearchBarDiv = true" ui-sref-active="md-warn" ui-sref="search">
	                                search<md-tooltip>Search Website</md-tooltip>
	                            </md-icon>
			                            						          
						</span><!-- small mobile menu -->
						<!--  ajpmAllSmallMobileIcons -->

						<span id="ajpmMainMobileIcons" hide-xl hide-lg hide-md show-sm show-xs>
			                            
                            <md-icon aria-label="home"  ui-sref-active="md-warn" ui-sref="home" >
                                home<md-tooltip>Home</md-tooltip>
                            </md-icon>

                            <md-icon aria-label="About Us" ui-sref-active="md-warn" ui-sref="about_us">
                                business<md-tooltip>About Us</md-tooltip>
                            </md-icon>

                            <md-icon aria-label="Catalog" ui-sref-active="md-warn" ui-sref="item_catalog">
                                shop<md-tooltip>Catalog</md-tooltip>
                            </md-icon>
			                                                        
						</span>
						<!-- ajpmMainMobileIcons -->
												
						<span id="ajpmMainIPadIcons" show-xl show-lg show-md hide-sm hide-xs>
			                            
                            <md-button class="md-raised" aria-label="Home" ui-sref-active="md-warn" ui-sref="home" >
                                <md-tooltip>Home</md-tooltip>
								Home
                            </md-button>

                            <md-button class="md-raised" aria-label="About Us" ui-sref-active="md-warn" ui-sref="about_us">
                                <md-tooltip>About Us</md-tooltip>
								About Us
                            </md-button>

                            <md-button class="md-raised" aria-label="Catalog" ui-sref-active="md-warn" ui-sref="item_catalog">
                                <md-tooltip>Catalog</md-tooltip>
								Catalog
                            </md-button>
			                                                        
						</span>
						<!-- ajpmMainIPadIcons -->
        
						<span flex></span>
			
						<span id="ajpmSearch" show-xl show-lg show-md show-sm hide-xs>
			
                                <form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()">

                                    <md-input-container>
                                        <label for="ajpmIdSearchInput">Search</label>

                                        <input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />

                                        <div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
                                            <div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
                                        </div>
                                    </md-input-container>

		                            <md-icon aria-label="Search" ui-sref-active="md-warn" ui-sref="search">
		                                search<md-tooltip>Search Website</md-tooltip>
		                            </md-icon>

                                </form>
			
						</span>
						<!-- ajpmSearch -->
			
						<span flex></span>

						<span id="ajpmExtraIPadIcons" show-xl show-lg show-md hide-sm hide-xs>

                            <md-button class="md-raised" aria-label="Contact Us" ui-sref-active="md-warn" ui-sref="contact_us">
                                <md-tooltip>Our Contact Information</md-tooltip>
								Contact Us
                            </md-button>

							<span ng-show="isAuthenticated">
								<md-button class="md-raised" aria-label="Logout" ui-sref-active="md-warn" ui-sref="logout" ng-click="isAuthenticated = false">
                                	<md-tooltip>User Logout</md-tooltip>
									Logout
								</md-button>
                            	
                            	<md-button class="md-raised" aria-label="My" ui-sref-active="md-warn" ui-sref="my_account">
									<md-tooltip>My Profile</md-tooltip>
									My
                            	</md-button>
                            
							</span>

                           <span  ng-hide="isAuthenticated">
								<md-button class="md-raised" aria-label="Login" ui-sref-active="md-warn" ui-sref="login1">
                                	<md-tooltip>User Login</md-tooltip>
									Login
								</md-icon-button>
                           </span>
			
						</span>
						<!-- ajpmExtraIPadIcons -->
						
			                            
						<span id="ajpmExtraMobileIcons" hide-xl hide-lg hide-md show-sm show-xs>

                            <md-icon aria-label="Contact Us" ui-sref-active="md-warn" ui-sref="contact_us">
                                contacts<md-tooltip>Our Contact Information</md-tooltip>
                            </md-icon>

							<span ng-show="isAuthenticated">
								<md-icon aria-label="Logout" ui-sref-active="md-warn" ui-sref="logout" ng-click="isAuthenticated = false">
                                	cancel<md-tooltip>User Logout</md-tooltip>A
								</md-icon>
								
	                            <md-icon aria-label="My" ui-sref-active="md-warn" ui-sref="my_account">
									person<md-tooltip>My Profile</md-tooltip>
	                            </md-icon>								
                           </span>

                           <span  ng-hide="isAuthenticated">
								<md-icon aria-label="Login" ui-sref-active="md-warn" ui-sref="login1">
                                	input<md-tooltip>User Login</md-tooltip>
								</md-icon>
                           </span>
			
						</span>
						<!-- ajpmExtraMobileIcons -->
			                
					</div>
					<!-- class=md-toolbar-tools -->
			                        
					<div class="md-toolbar-tools"  layout="row" ng-show="showSearchBarDiv">
			
						<md-icon ng-click="showSearchBarDiv=false">
							cancel<md-tooltip>Cancel</md-tooltip>
						</md-icon>
			
						<form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()">
			
							<md-input-container>
								<label for="ajpmIdSearchInput">Search</label>
			
								<input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />
			
								<div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
									<div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
								</div>
							</md-input-container>
			
						</form>
			
					</div>
			
				</md-toolbar>                
			
			</div>
			<!-- ajpmMenu -->

		</div>
		<!-- ajpmHeaderAndMenu-->

		<div id="ajpmViewAndFooter" layout="column" layout-margin>
			<div id="ajpmMessage">
				<div ng-show="hasPageMessages()">
					<ul>
						<li ng-repeat="pageMessage in getPageMessages()">
							{{pageMessage}}
						</li>
					</ul>
				</div>
			</div>
			<!-- ajpmMessage -->

			<div id="ajpmView">
				 <div ui-view>Loading...</div>
			</div>
			<!-- ajpmView -->

			<div id="ajpmFooter">
                	&copy; Copyright 
                	<?php if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
						echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
					} else {
						echo '<a href="/">Our WEB Presence</a>';
					}?>
			</div>
			<!-- ajpmFooter -->

		</div>
		<!-- ajpmViewAndFooter -->

            
	</div>
	<!-- ajpmMainRow -->
      
</md-content>

<?php if(file_exists(dirname(__FILE__) . '/../_body_links.php'))
        include dirname(__FILE__) . '/../_body_links.php';
?>

<base href="/">
<md-content layout-fill>

	<div id="ajpmMainRow" layout="column">

		<div flex id="ajpmHeaderAndMenu" layout="column">

			<div flex id="ajpmHeader" layout="row" layout-xs="column">

				<div id="ajpmLogo">

					<?php if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
						echo '<img ng-src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" />';
					}?>

				</div><!-- ajpmLogo -->
				 
				<div flex id="ajpmNameStatement" layout="column" >

					<div flex id="ajpmName">

						<h1><?php if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
							echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
						} else {
							echo '<a href="/">Our WEB Presence</a>';
						}?></h1>

					</div><!-- ajpmName -->

					<div flex id="ajpmStatement" >

						<h2><?php if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
							echo $_SESSION ['url_domain_org'] ['statement'];
						} else {
							echo "Best Presence on Web";
						}?></h2>

					</div><!-- ajpmStatement -->
					
				</div><!-- ajpmNameStatement -->

			</div><!-- ajpmHeader -->

			<div id="ajpmMenu" >
			
				<md-toolbar>
			                    
					<div class="md-toolbar-tools" ng-hide="showSearchBarDiv">
			
						<span id="ajpmAllIcons" hide-gt-xs show-xs>
									          
								<md-menu>
      						          
									<md-icon-button aria-label="Menu" ng-click="$mdOpenMenu()">
                                		<md-tooltip>Menu</md-tooltip>
										<i class="material-icons">menu</i>
                            		</md-icon-button>

						            <md-menu-content >
						            
										<md-menu-item ui-sref-active="md-active" ui-sref="home">
										<md-button aria-label="Home">
											<i class="material-icons">home</i> Home
										</md-button>
										</md-menu-item>

										<md-menu-item ui-sref-active="md-active" ui-sref="about_us">
			                            <md-button aria-label="About Us">
											<i class="material-icons">business</i> About Us
			                            </md-button>
										</md-menu-item>
						
										<md-menu-item ui-sref-active="md-active" ui-sref="item_catalog">
			                            <md-button aria-label="Catalog">
											<i class="material-icons">shop</i> Catalog
			                            </md-button>
										</md-menu-item>
						       			
										<md-menu-divider></md-menu-divider>
																				
										<md-menu-item ui-sref-active="md-active" ui-sref="contact_us">
			                            <md-button aria-label="Contact Us">
											<i class="material-icons">contacts</i> Contact Us
			                            </md-button>
										</md-menu-item>
						
										<md-menu-item ng-show="isAuthenticated">
										<md-button aria-label="Logout" ui-sref-active="md-active" ui-sref="logout">
											<i class="material-icons">camcel</i> Logout
										</md-button>
										</md-menu-item>						            

										<md-menu-item ng-hide="isAuthenticated">
										<md-button aria-label="Login" ui-sref-active="md-active" ui-sref="login1">
											<i class="material-icons">input</i> Login
										</md-button>
										</md-menu-item>

										<md-menu-item ui-sref-active="md-active" ui-sref="my_account">
			                            <md-button aria-label="My">
											<i class="material-icons">person</i> My
			                            </md-button>
										</md-menu-item>
																	
						            </md-menu-content>
						            
						          </md-menu>
						          
	                            <md-icon-button hide-gt-sm ng-click="showSearchBarDiv = true" ui-sref-active="md-active" ui-sref="search">
	                                <md-tooltip>Search</md-tooltip>
									<i class="material-icons">search</i>
	                            </md-icon-button>
			                            						          
						</span>
						<!--  ajpmAllIcons -->
			                        		
						<span id="ajpmMainIcons" show-gt-xs hide-xs>
			                            
                            <md-icon-button aria-label="Home" ui-sref-active="md-active" ui-sref="home" >
                                <md-tooltip>Home</md-tooltip>
								<i class="material-icons">home</i>
                            </md-icon-button>

                            <md-icon-button aria-label="About Us" ui-sref-active="md-active" ui-sref="about_us">
                                <md-tooltip>About Us</md-tooltip>
								<i class="material-icons">business</i>
                            </md-icon-button>

                            <md-icon-button aria-label="Catalog" ui-sref-active="md-active" ui-sref="item_catalog">
                                <md-tooltip>Catalog</md-tooltip>
								<i class="material-icons">shop</i>
                            </md-icon-button>
			                                                        
						</span>
						<!-- ajpmMainIcons -->
			                            
						<span flex></span>
			
						<span id="ajpmSearch" show-gt-sm hide-sm hide-xs>
			
                                <form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()">

                                    <md-input-container>
                                        <label for="ajpmIdSearchInput">Search</label>

                                        <input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />

                                        <div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
                                            <div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
                                        </div>
                                    </md-input-container>

		                            <md-icon-button ui-sref-active="md-active" ui-sref="search">
		                                <md-tooltip>Search</md-tooltip>
										<i class="material-icons">search</i>
		                            </md-icon-button>

                                </form>
			
						</span>
						<!-- ajpmSearch -->
			
						<span flex></span>
			                            
						<span id="ajpmExtraIcons" show-gt-xs hide-xs>
				                            
				                            <md-icon-button hide-gt-sm ng-click="showSearchBarDiv=true" ui-sref-active="md-active" ui-sref="search">
				                                <md-tooltip>Search</md-tooltip>
												<i class="material-icons">search</i>
				                            </md-icon-button>
				
				                            <md-icon-button ui-sref-active="md-active" ui-sref="contact_us">
				                                <md-tooltip>Contact Us</md-tooltip>
												<i class="material-icons">contacts</i>
				                            </md-icon-button>
				
											<span ng-show="isAuthenticated">
												<md-icon-button ui-sref-active="md-active" ui-sref="logout">
				                                	<md-tooltip>Logout</md-tooltip>
													<i class="material-icons">Logout</i>
												</md-icon-button>
				                           </span>
				
				                           <span  ng-hide="isAuthenticated">
												<md-icon-button ui-sref-active="md-active" ui-sref="login1">
				                                	<md-tooltip>Login</md-tooltip>
													<i class="material-icons">input</i>
												</md-icon-button>
				                           </span>
			
				                            <md-icon-button aria-label="My" ui-sref-active="md-active" ui-sref="my_account">
												<md-tooltip>My Profile</md-tooltip>
												<i class="material-icons">person</i>
				                            </md-icon-button>
			
						</span>
						<!-- ajpmExtraIcons -->
			                
					</div>
					<!-- class=md-toolbar-tools -->
			                        
					<div class="md-toolbar-tools"  layout="row" ng-show="showSearchBarDiv">
			
						<md-icon-button ng-click="showSearchBarDiv=false">
							<md-tooltip>Cancel</md-tooltip>
							<i class="material-icons">cancel</i>
						</md-icon-button>
			
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


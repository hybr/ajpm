<base href="/">

<md-content>

        <div id="ajpmMainRow" layout="column">

            <div flex id="ajpmHeaderAndMenu" layout="column">

                <div flex id="ajpmHeader" layout="row" layout-xs="column">

                    <div id="ajpmLogo">

                        <?php if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
                            echo '<img ng-src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" />';
                        }?>

                    </div>
                    <!-- ajpmLogo -->

                    <div flex id="ajpmNameSearchButtonStatement" layout="column"  >

                        <div flex id="ajpmNameSearchButton" layout="row" layout-xs="column" layout-align="start center">

                            <div flex id="ajpmName">

                                <h1><?php if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
                                    echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
                                } else {
                                    echo '<a href="/">Our WEB Presence</a>';
                                }?></h1>

                            </div>


                        </div>
                        <!-- ajpmNameSearchButton -->

                        <div flex id="ajpmStatement" >

                            <h2><?php if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
                        echo $_SESSION ['url_domain_org'] ['statement'];
                } else {
                        echo "Best Presence on Web";
                }?></h2>

                        </div>
                        <!-- ajpmStatement -->

                    </div>
                    <!-- ajpmNameSearchButtonStatement -->

                </div>
                <!-- ajpmHeader -->

                <div flex id="ajpmMenu" >

                    <md-toolbar>
                    
                        <div class="md-toolbar-tools" ng-hide="showSearchBarDiv">

                        	<span id="ajpmAllIcons" hide-gt-xs show-xs>
						          
						          <md-menu>
      						          
									<md-icon-button aria-label="Menu" class="md-icon-button" ng-click="$mdOpenMenu()">
                                		<md-tooltip>Menu</md-tooltip>
										<i class="material-icons">menu</i>
                            		</md-icon-button>
                            	
						            
						            <md-menu-content >
						            
						            
						              <md-menu-item ui-sref-active="md-active" ui-sref="home">
			                            <md-button>
											<span class="material-icons">home</span> Home
			                            </md-button>
						              </md-menu-item>

						              <md-menu-item ui-sref-active="md-active" ui-sref="about_us">
			                            <md-button>
											<span class="material-icons">business</span> About Us
			                            </md-button>
						              </md-menu-item>
						
						              <md-menu-item ui-sref-active="md-active" ui-sref="item_catalog">
			                            <md-button>
											<span class="material-icons">shop</span> Catalog
			                            </md-button>
						              </md-menu-item>
						       			
						       		<md-menu-divider></md-menu-divider>

						            <md-menu-item ui-sref-active="md-active" ui-sref="contact_us">
			                            <md-button>
											<span class="material-icons">contacts</span> Contact Us
			                            </md-button>
						            </md-menu-item>
						
						             <md-menu-item ng-show="isAuthenticated">
										<md-button ui-sref-active="md-active" ui-sref="logout">
											<span class="material-icons">Logout</span> Logout
										</md-button>
						             </md-menu-item>						            

									<md-menu-item class="md-indent" ng-hide="isAuthenticated">
										<md-button ui-sref-active="md-active" ui-sref="login1">
											<span class="material-icons">input</span> Login
										</md-button>
 									</md-menu-item>

						            </md-menu-content>
						          </md-menu>
                     	
                        	</span>
                        	<!--  ajpmAllIcons -->
                        		
                        	<span id="ajpmMainIcons" show-gt-xs hide-xs>
                            
                            <md-button class="md-icon-button" aria-label="Home" ui-sref-active="md-active" ui-sref="home" >
                                <md-tooltip>Home</md-tooltip>
								<i class="material-icons">home</i>
                            </md-button>

                            <md-button class="md-icon-button" aria-label="About Us" ui-sref-active="md-active" ui-sref="about_us">
                                <md-tooltip>About Us</md-tooltip>
								<i class="material-icons">business</i>
                            </md-button>

                            <md-button class="md-icon-button" aria-label="Catalog" ui-sref-active="md-active" ui-sref="item_catalog">
                                <md-tooltip>Catalog</md-tooltip>
								<i class="material-icons">shop</i>
                            </md-button>
                                                        
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

		                            <md-button class="md-icon-button" ui-sref-active="md-active" ui-sref="search2">
		                                <md-tooltip>Search</md-tooltip>
										<i class="material-icons">search</i>
		                            </md-button>

                                </form>

                            </span>
                            <!-- ajpmSearch -->

                            <span flex></span>
                            
                            <span id="ajpmExtraIcons" show-gt-xs hide-xs>
                            
                            <md-button class="md-icon-button" hide-gt-sm ng-click="showSearchBarDiv = true">
                                <md-tooltip>Search</md-tooltip>
								<i class="material-icons">search</i>
                            </md-button>

                            <md-button class="md-icon-button" ui-sref-active="md-active" ui-sref="contact_us">
                                <md-tooltip>Contact Us</md-tooltip>
								<i class="material-icons">contacts</i>
                            </md-button>

							<span  ng-show="isAuthenticated">
								<md-button class="md-icon-button " ui-sref-active="md-active" ui-sref="logout">
                                	<md-tooltip>Logout</md-tooltip>
									<i class="material-icons">Logout</i>
								</md-button>
                           </span>

                           <span  ng-hide="isAuthenticated">
								<md-button class="md-icon-button" ui-sref-active="md-active" ui-sref="login1">
                                	<md-tooltip>Login</md-tooltip>
									<i class="material-icons">input</i>
								</md-button>
                           </span>

                           </span>
                           <!-- ajpmExtraIcons -->
                           
                        </div>
                        <!-- class=md-toolbar-tools -->
                                                    
                        <div layout="row" class="md-toolbar-tools" show-sm show-md hide-gt-md ng-show="showSearchBarDiv">

                            <md-button class="md-icon-button" ng-click="showSearchBarDiv = false">
                                <md-tooltip>Back</md-tooltip>
								<i class="material-icons">arrow_back</i>
                            </md-button>

                                <form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()">

                                    <md-input-container>
                                        <label for="ajpmIdSearchInput">Search</label>

                                        <input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />

                                        <div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
                                            <div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
                                        </div>
                                    </md-input-container>

		                            <md-button class="md-icon-button" ui-sref-active="md-active" ui-sref="search2">
		                                <md-tooltip>Search</md-tooltip>
										<i class="material-icons">search</i>
		                            </md-button>
		                            
                                </form>

                        </div>

                    	</md-toolbar>
                    
                            <span id="ajpmSearch" hide-gt-xs show-xs>

                                <form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()" layout="row">

                                    <md-input-container>
                                        <label for="ajpmIdSearchInput">Search</label>

                                        <input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />

                                        <div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
                                            <div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
                                        </div>
                                    </md-input-container>

		                            <md-button class="md-icon-button" ui-sref-active="md-active" ui-sref="search2">
		                                <md-tooltip>Search</md-tooltip>
										<i class="material-icons">search</i>
		                            </md-button>

                                </form>

                            </span>                    

                </div>
                <!-- ajpmMenu -->

            </div>
            <!-- ajpmHeaderAndMenu-->

            <div flex id="ajpmViewAndFooter" layout="column">

                <div flex id="ajpmMessage">
                    &nbsp;
                    <div ng-show="hasPageMessages()">
                        <ul>
                            <li ng-repeat="pageMessage in getPageMessages()">
                                {{pageMessage}}
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- ajpmMessage -->

                <div flex id="ajpmView">
                    <md-content ui-view>Loading...</md-content>
                </div>
                <!-- ajpmView -->

                <div flex id="ajpmFooter" flex>
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


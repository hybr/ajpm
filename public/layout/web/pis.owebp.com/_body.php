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
                            <!-- ajpmName -->

                            <div flex id="ajpmSearch">

                                <form name="ajpmFormSearch" novalidate="" method="POST" ng-submit="ajpmFormSearch.$valid && searchIt()">

                                    <md-input-container>
                                        <label for="ajpmIdSearchInput">Search</label>

                                        <input type="text" id="ajpmIdSearchInput" name="nameSearchPattern" ng-model="searchPattern" required="" />

                                        <div ng-messages="ajpmFormSearch.nameSearchPattern.$error" ng-show="ajpmFormSearch.nameSearchPattern.$dirty">
                                            <div id="ajpmSearchInputRequiredError" ng-message="required">Search pattern is required</div>
                                        </div>
                                    </md-input-container>

                                    <md-input-container>
                                        <input id="ajpmIdSearchButton" name="nameSearchButton" type="submit" value="Go" ui-sref="search2"/>
                                    </md-input-container>

                                </form>

                            </div>
                            <!-- ajpmSearch -->

                            <div flex id="ajpmButton" layout="row"  layout-xs="column" layout-align="end center">

                                <span>
                                    <md-button class="md-primary md-raised" aria-label="My" ui-sref-active="is-active" ui-sref="my_account">My</md-button>
                                </span>

                                <span>
                                    <md-button class="md-primary md-raised" aria-label="Contact" ui-sref-active="is-active" ui-sref="contact_us">Contact</md-button>
                                </span>

                                <span  ng-show="isAuthenticated">
                                    <md-button class="md-primary md-raised" aria-label="Logout" ui-sref-active="is-active" ui-sref="logout">Logout</md-button>
                                </span>

                                <span  ng-hide="isAuthenticated">
                                    <md-button class="md-primary md-raised" aria-label="Login" ui-sref-active="is-active" ui-sref="login1">Login</md-button>
                                </span>

                            </div>
                            <!-- ajpmButton -->

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

                            <md-button class="md-icon-button" aria-label="Icon">
                                <md-tooltip>Icon</md-tooltip>
                                <md-icon class="md-default-theme" md-svg-icon="fb.svg"></md-icon>
                            </md-button>

                            <h2>
                                <span>Heading</span>
                            </h2>

                            <md-input-container md-no-float class="md-accent" flex="50" style="padding-bottom:0px;margin-left:25px" show-gt-md hide-sm hide-md>
                                <md-icon style="color:wheat" class="material-icons">&#xE8B6;</md-icon>
                                <input ng-model="searchInput" placeholder="Search here" style="color:wheat;padding-left:25px;margin-left:5px">
                            </md-input-container>

                            <span flex></span>
                            <md-button class="md-icon-button" hide-gt-md show-sm show-md ng-click="showSearchBarDiv = true">
                                <md-tooltip>Search</md-tooltip>
                                <img src="search.png" style="width:24px;height:24px;"/>
                            </md-button>
                            <md-button class="md-icon-button" show-gt-sm hide-sm>
                                <md-tooltip>Pen</md-tooltip>
                                <img src="pen.png" style="width:24px;height:24px;"/>
                            </md-button>
                            <md-button class="md-icon-button" show-gt-sm hide-sm>
                                <md-tooltip>Messages</md-tooltip>
                                <img src="messages.png" style="width:24px;height:24px;"/>
                            </md-button>
                            <md-button class="md-icon-button" show-gt-sm hide-sm>
                                <md-tooltip>User</md-tooltip>
                                <img src="user.png" style="width:24px;height:24px;"/>
                            </md-button>
                            <md-button class="md-icon-button" show-gt-sm hide-sm>
                                <md-tooltip>Settings</md-tooltip>
                                <img src="settings.png" style="width:24px;height:24px;"/>
                            </md-button>
                            <md-button class="md-icon-button" hide-gt-sm show-sm>
                                <md-tooltip>Menu</md-tooltip>
                                <img src="menu.png" style="width:24px;height:24px;"/>
                            </md-button>
                        </div>

                        <div layout="row" class="md-toolbar-tools" show-sm show-md hide-gt-md ng-show="showSearchBarDiv">

                            <md-button class="md-icon-button" ng-click="showSearchBarDiv = false">
                                <img src="back.png" style="width:24px;height:24px;"/>
                            </md-button>

                            <md-input-container flex="80" md-no-float class="md-accent" style="padding-bottom:0px;">
                                <md-icon style="color:wheat" class="material-icons">&#xE8B6;</md-icon>
                                <input ng-model="searchInput" placeholder="Search here" style="color:wheat;padding-left:25px;margin-left:5px">
                            </md-input-container>

                            <span flex></span>
                            <md-button class="md-icon-button">
                                <img src="search.png" style="width:24px;height:24px;"/>
                            </md-button>
                        </div>

                    </md-toolbar>


                    <md-toolbar>
                        <md-menu-bar>

                            <md-menu>
                                <button ng-click="$mdOpenMenu()">Home</button>

                                <md-menu-content>
                                    <md-menu-item class="md-indent">
                                        <md-icon md-svg-icon="img/icons/ic_home_black_48px.svg"></md-icon>
                                        <md-button ui-sref-active="is-active" ui-sref="home">Home</md-button>
                                    </md-menu-item>

                                    <md-menu-divider></md-menu-divider>

                                    <md-menu-item class="md-indent">
                                        <md-button ui-sref-active="is-active" ui-sref="contact_us">Contact Us
                                        </md-button>
                                    </md-menu-item>

                                    <md-menu-item class="md-indent">
                                        <md-button ui-sref-active="is-active" ui-sref="login1">Login</md-button>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>

                            <md-menu>
                                <button ng-click="$mdOpenMenu()">About</button>

                                <md-menu-content>
                                    <md-menu-item class="md-indent">
                                        <md-button ui-sref-active="is-active" ui-sref="about_us">Why Us</md-button>
                                    </md-menu-item>

                                </md-menu-content>
                            </md-menu>

                            <md-menu>
                                <button ng-click="$mdOpenMenu()">Catalog</button>
                                <md-menu-content>
                                    <md-menu-item class="md-indent">
                                        <md-button ui-sref-active="is-active" ui-sref="item_catalog">Services
                                        </md-button>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>

                        </md-menu-bar>
                    </md-toolbar>

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

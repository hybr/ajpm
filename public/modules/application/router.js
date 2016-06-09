/**
 * Angular router for ui-view
 */


angular.module('ajpmApp').config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES',
    function($stateProvider, $urlRouterProvider, USER_ROLES) {

	$urlRouterProvider.otherwise("/home");

	$stateProvider.state('home', {
		url : "/home",
		templateUrl : "modules/web_page/"+viewType+"view.html",
		controller : "HomeController"
	}).state('about_us', {
		url : "/about_us",
		templateUrl : "modules/web_page/"+viewType+"view.html",
		controller : "AboutUsController"
	}).state('contact_us', {
		url : "/contact_us",
		templateUrl : "modules/contact_us/"+viewType+"view.html",
		controller : 'ContactUsController'
	}).state('my_account', {
		url : "/my_account",
		templateUrl : "modules/my_account/"+viewType+"view.html",
		controller : 'PersonController'
	}).state('logout', {
		url : "/logout",
		template : "<div class='ui-state-highlight'> You are logged out. </div>",
		controller : 'LogoutController'
	}).state('join', {
		url : "/join",
		templateUrl : "modules/join/"+viewType+"join.html",
		controller : 'JoinController'
	}).state('forgot', {
		url : "/forgot",
		templateUrl : "modules/auth/"+viewType+"forgot.html",
		controller : 'ForgotController'
	}).state('login1', {
		url : "/login1",
		templateUrl : "modules/auth/"+viewType+"login_s1.html",
		controller : 'LoginController'
	}).state('login2', {
		url : "/login2",
		templateUrl : "modules/auth/"+viewType+"login_s2.html",
		controller : 'LoginController'
	}).state('search', {
		url : "/search",
		templateUrl : "modules/search/"+viewType+"view.html",
		controller : 'SearchController'
	}).state('web_page', {
		url : "/web_page/:webPageId",
		templateUrl : "modules/web_page/"+viewType+"view.html",
		controller : 'WebPageController'
	}).state('item_catalog', {
		url : "/item_catalog",
		templateUrl : "modules/item_catalog/"+viewType+"view.html",
		controller : 'ItemCatalogController'
	}).state('item', {
		url : "/item/:itemId",
		templateUrl : "modules/item/"+viewType+"view.html",
		controller : 'ItemController'			
	}).state('real_estate_asset', {
		url : "/real_estate_asset/:realEstateAssetId",
		templateUrl : "modules/real_estate_asset/"+viewType+"view.html",
		controller : 'RealEstateAssetController'
	}).state('activities', {
		url : "/activities",
		templateUrl : "modules/activity/"+viewType+"listView.html",
		controller : 'ActivitiesController'
	}).state('activity', {
		url : "/activity/:activityId",
		templateUrl : "modules/activity/"+viewType+"view.html",
		controller : 'ActivityController'				
	}).state('admin_dashboard', {
		url : "/admin_dashboard",
		templateUrl : "modules/admin_dashboard/"+viewType+"view.html",
		/* if roles are specified then it will check for authorization
		 * else the state is considerd for public use
		 */
		data : {
			authorizedRoles: [USER_ROLES.admin]
		}
	});

    /*check browser support */
    /*
     if(window.history && window.history.pushState){
        $locationProvider.html5Mode({
        	enabled: true,
            requireBase: false
      });
    }*/

}]);

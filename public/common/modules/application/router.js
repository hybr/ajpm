/**
 * Angular router for ui-view
 */


angular.module('ajpmApp').config(['$locationProvider', '$stateProvider', '$urlRouterProvider', 'USER_ROLES',
    function($locationProvider, $stateProvider, $urlRouterProvider, USER_ROLES) {

	$urlRouterProvider.otherwise("home");

	$stateProvider.state('home', {
		url : "/home",
		templateUrl : "/common/modules/web_page/"+viewType+"view.html",
		controller : "HomeController"
	}).state('about_us', {
		url : "/about_us",
		templateUrl : "/common/modules/web_page/"+viewType+"view.html",
		controller : "AboutUsController"
	}).state('contact_us', {
		url : "/contact_us",
		templateUrl : "/common/modules/contact_us/"+viewType+"view.html",
		controller : 'ContactUsController'
	}).state('my_account', {
		url : "/my_account",
		templateUrl : "/common/modules/my_account/"+viewType+"view.html",
		controller : 'PersonController'
	}).state('logout', {
		url : "/logout",
		template : "<div class='ui-state-highlight'> You are logged out. </div>",
		controller : 'LogoutController'
	}).state('join', {
		url : "/join",
		templateUrl : "/common/modules/auth/"+viewType+"join.html",
		controller : 'JoinController'
	}).state('forgot', {
		url : "/forgot",
		templateUrl : "/common/modules/auth/"+viewType+"forgot.html",
		controller : 'ForgotController'
	}).state('login1', {
		url : "/login1",
		templateUrl : "/common/modules/auth/"+viewType+"login_s1.html",
		controller : 'LoginController'
	}).state('login2', {
		url : "/login2",
		templateUrl : "/common/modules/auth/"+viewType+"login_s2.html",
		controller : 'LoginController'
	}).state('search', {
		url : "/search",
		templateUrl : "/common/modules/search/"+viewType+"view.html",
		controller : 'SearchController'
	}).state('web_page', {
		url : "/web_page/:webPageId",
		templateUrl : "/common/modules/web_page/"+viewType+"view.html",
		controller : 'WebPageController'
	}).state('item_catalog', {
		url : "/item_catalog",
		templateUrl : "/common/modules/item_catalog/"+viewType+"view.html",
		controller : 'ItemCatalogController'
	}).state('item', {
		url : "/item/:itemId",
		templateUrl : "/common/modules/item/"+viewType+"view.html",
		controller : 'ItemController'			
	}).state('real_estate_asset', {
		url : "/real_estate_asset/:realEstateAssetId",
		templateUrl : "/common/modules/real_estate_asset/"+viewType+"view.html",
		controller : 'RealEstateAssetController'
	}).state('activities', {
		url : "/activities",
		templateUrl : "/common/modules/activity/"+viewType+"listView.html",
		controller : 'ActivitiesController'
	}).state('activity', {
		url : "/activity/:activityId",
		templateUrl : "/common/modules/activity/"+viewType+"view.html",
		controller : 'ActivityController'				
	}).state('admin_dashboard', {
		url : "/admin_dashboard",
		templateUrl : "/common/modules/admin_dashboard/"+viewType+"view.html",
		/* if roles are specified then it will check for authorization
		 * else the state is considerd for public use
		 */
		data : {
			authorizedRoles: [USER_ROLES.admin]
		}
	});
	
	// $scope.urlPath = 	$location.path(); 
	
	$locationProvider.html5Mode(true);
	
    /*check browser support */
    /*
     if(window.history && window.history.pushState){
        $locationProvider.html5Mode({
        	enabled: true,
            requireBase: false
      });
    }*/

}]);

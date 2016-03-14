/**
 * Angular router for ui-view
 */

angular.module('ajpmApp').config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES',
    function($stateProvider, $urlRouterProvider, USER_ROLES) {

	$urlRouterProvider.otherwise("/home");

	$stateProvider.state('home', {
		url : "/home",
		templateUrl : "modules/home/view.html"
	}).state('home.list', {
		url : "/list",
		templateUrl : "modules/home/list.html",
		controller : function($scope) {
			$scope.items = [ "A", "List", "Of", "Items" ];
		}
	}).state('about_us', {
		url : "/about_us",
		templateUrl : "modules/about_us/view.html"

	}).state('about_us.list', {
		url : "/list",
		templateUrl : "modules/about_us/list.html",
		controller : function($scope) {
			$scope.things = [ "A", "Set", "Of", "Things" ];
		}
	}).state('contact_us', {
		url : "/contact_us",
		templateUrl : "modules/contact_us/view.html",
		controller : 'ContactUsController'
	}).state('my_account', {
		url : "/my_account",
		templateUrl : "modules/my_account/view.html"
	}).state('logout', {
		url : "/logout",
		template : "<div class='ui-state-highlight'> You are logged out. </div>",
		controller : 'LogoutController'
	}).state('login1', {
		url : "/login1",
		templateUrl : "modules/auth/login_s1.html",
		controller : 'LoginController'
	}).state('login2', {
		url : "/login2",
		templateUrl : "modules/auth/login_s2.html",
		controller : 'LoginController'			
	}).state('admin_dashboard', {
		url : "/admin_dashboard",
		templateUrl : "modules/admin_dashboard/view.html",
		data : {
			authorizedRoles: [USER_ROLES.admin]
		}
	});

	// enable HTML5mode to disable hashbang urls
    // $locationProvider.html5Mode(true);
    
}]);

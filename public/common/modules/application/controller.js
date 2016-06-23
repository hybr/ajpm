
/**
 * @controller ApplicationController to maintain application wide variables and
 *             functions It is defined here as it is using the authentication
 *             service and other information from this file
 */

'use strict';

angular.module('ajpmApp').controller('ApplicationController',
	['$scope', '$rootScope', '$http', '$location', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
	function($scope, $rootScope, $http, $location, AuthService, SessionService, AUTH_EVENTS, $state){

	$rootScope.nextRequestedStateName =  '';


	/**
	 * this is the parent controller for all controllers.
 	 * Manages auth login functions and each controller
	 * inherits from this controller
	 */ 	

	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthorized = AuthService.isAuthorized();

	/**
	 * If user is logged in then load the userRecord when page is refreshed
	 */
	$scope.currentUserEmail = SessionService.getCurrentUserEmail();

	/**
	 * Main page message functions
	 */
	var pageMessages = [];

	$rootScope.pushPageMessage = function(message) {
		pageMessages.push(message);
	}

	$rootScope.hasPageMessages = function() {
		return (pageMessages.length > 0);
	}

	$rootScope.clearPageMessages = function() {
		pageMessages = [];
	}

	$rootScope.getPageMessages = function() {
		return pageMessages;
	}

	/**
	 * meta tags management 
	 */
	$scope.metaTags = {
		'title' : 'Welcome',
		'description': 'Welcome home',
		'keywords': 'home, welcome'
	};
 
	$scope.setMetaTag = function (tag, value) {
		$scope.metaTags[tag]  = value;
	}
	
	$scope.appendMetaTag = function (tag, value) {
		$scope.metaTags[tag]  = $scope.metaTags[tag] + value;
	}
	
	/* to get meta tag use {{ metaTags.title }} */
	
	/**
	 * Auth events functions
	 *
	 */

	var showNotAuthorized = function(){
		if (AuthService.isAuthenticated) {
			$rootScope.pushPageMessage("You are not authorized");
		} else {
			$rootScope.pushPageMessage("Please login first");
			$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
		}
	}

	var loginPass = function() {
		$state.go($rootScope.nextRequestedStateName);
	}

	var logoutPass = function() {
		$state.go('home');
	}

	/* listen to events of unsuccessful logins, to run the login dialog */
	$rootScope.$on(AUTH_EVENTS.notAuthorized, showNotAuthorized);
	$rootScope.$on(AUTH_EVENTS.loginSuccess, loginPass);
	$rootScope.$on(AUTH_EVENTS.logoutSuccess, logoutPass);

	
	/* counter management functions */
	$scope.increaseCounter1 = function(counter, limit, step) {
		if ((counter+step) <= limit) counter = counter + step;
		return counter;
	}
	
	$scope.decreaseCounter1 = function(counter, limit, step) {
		if ((counter-step) >= limit) counter = counter - step;
		return counter;
	}	

	/* Get the web page id for the about us page */

	$http({
		method: 'POST',
		url: '/common/service.php/custom_request/url_domain_org',
	}).then(function successCallback(response) {
		if (response.status != 200) {
			/* connection error with server_side */
			$rootScope.pushPageMessage(response);
		} else {
			$scope.orgRecord = response.data;
		}
	}, function errorCallback(response) {
		$rootScope.pushPageMessage(response);
	});
	
} ]);

/*
ajpmApp.config(['$scope', '$mdThemingProvider', function($scope, $mdThemingProvider) {
	var colors = $scope.orgRecord.web_site_theme_2.split("_");
	if(typeof colors[0] === 'undefined') colors[0] = 'indigo';
	if(typeof colors[1] === 'undefined') colors[1] = 'brown';
	if(typeof colors[2] === 'undefined') colors[2] = 'orange';
	if(typeof colors[3] === 'undefined') colors[3] = 'amber';
	if(typeof colors[4] === 'undefined') colors[4] = '';
	$mdThemingProvider.theme('newTheme').primaryPalette(colors[0]);
	$mdThemingProvider.theme('newTheme').accentPalette(colors[1]);
	$mdThemingProvider.theme('newTheme').warnPalette(colors[2]);
	$mdThemingProvider.theme('newTheme').backgroundPalette(colors[3]);
	if (colors[4] == 'dark') {
		$mdThemingProvider.theme('newTheme').dark();
	}
	$mdThemingProvider.setDefaultTheme('newTheme');
}]);
*/

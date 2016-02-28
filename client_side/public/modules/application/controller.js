
/**
 * @controller ApplicationController to maintain application wide variables and
 *             functions It is defined here as it is using the authentication
 *             service and other information from this file
 */

'use strict';

angular.module('ajpmApp').controller('ApplicationController', 
	['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
	function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){
	
	$rootScope.nextRequestedStateName =  '';
	
	
	// this is the parent controller for all controllers.
	// Manages auth login functions and each controller
	// inherits from this controller	
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthorized = AuthService.isAuthorized();

	console.log('isAuthenticated ', $scope.isAuthenticated );
	
	
	/**
	 * If user is logged in then load the userRecord when page is refreshed
	 */
	$scope.currentUserEmail = SessionService.getCurrentUserEmail();
	
	/**
	 * Main page message functions
	 */
	var pageMessages = [];
	
	$scope.pushPageMessage = function(message) {
		pageMessages.push(message);
	}
	
	$scope.hasPageMessages = function() {
		return (pageMessages.length > 0);
	}
	
	$scope.clearPageMessages = function() {
		pageMessages = [];
	}
	
	$scope.getPageMessages = function() {
		return pageMessages;
	}
	
	/**
	 * Auth events functions
	 * 
	 */
	
	var showNotAuthorized = function(){
		if (AuthService.isAuthenticated) {
			$scope.pushPageMessage("You are not authorized");
		} else {
			$scope.pushPageMessage("Please login first");
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


} ]);

/**
 * @controller ApplicationController to maintain application wide variables and
 *             functions It is defined here as it is using the authentication
 *             service and other information from this file
 */

'use strict';

angular.module('ajpmApp').controller('ApplicationController', ['$scope', '$rootScope', 'AuthService', 'AUTH_EVENTS', '$window', '$state',
function($scope, $rootScope, AuthService, AUTH_EVENTS, $window, $state){
	
	// this is the parent controller for all controllers.
	// Manages auth login functions and each controller
	// inherits from this controller	
	
	$scope.loggedIn = AuthService.isAuthenticated();
	$scope.hasRoles = AuthService.isAuthorized();
	
	$scope.currentState =  null;
	
	/**
	 * If user is logged in then load the userRecord when page is refreshed
	 */
	if ($scope.loggedIn) {
		$scope.userRecord = $window.sessionStorage.getItem("userInfo");
	} else {
		$scope.userRecord = null;
	}
	console.log($scope.userRecord);
	
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
		alert("Not Authorized");
	}

	var loginPass = function() {
		$scope.pushPageMessage(" You are logged in. ");
		$state.go($rootScope.currentState);
	}
	
	
	/* listen to events of unsuccessful logins, to run the login dialog */
	$rootScope.$on(AUTH_EVENTS.notAuthorized, showNotAuthorized);
	$rootScope.$on(AUTH_EVENTS.loginSuccess, loginPass);

} ]);
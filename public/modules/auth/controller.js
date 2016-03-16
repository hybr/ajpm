'use strict';

/**
 * Controller to handle the user login
 */

angular.module('ajpmApp').controller('LoginController',	[
	'$rootScope',
	'$scope',
	'$state',
	'$window',
	'AuthService',
	'SessionService',
	'AUTH_EVENTS',
	function($rootScope, $scope, $state, $window, AuthService, SessionService, AUTH_EVENTS) {

		/**
		 * Initialize the user record before login form is shown
		 */
		$scope.credentials = {};
		$scope.form_login_s1 = {};
		$scope.form_login_s2 = {};
					
		// when the form step s1 is submitted
		$scope.submit_s1 = function() {
			
			$rootScope.clearPageMessages();
			
			
			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credentials.email
			 * 2. Send the encrypted credentials.email to server
			 * 3. Response will be any one of two
			 * 		3.1 Found user and return with a session_id
			 * 		3.2 No user found and return is empty string
			 */
			
			/**
			 * If user exists then we will get a valid session id to start the session from the server side
			 * if session_id is empty then user does not exists
			 */
			AuthService.checkUserForLoginProcess(
				$scope.email_address,
				checkUserForLoginProcessPass,
				checkUserForLoginProcessFail
			);
		};
		
		// when the form step s2 is submitted
		$scope.submit_s2 = function() {
			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credentials.email
			 * 2. Send the encrypted credentials.email to server
			 * 3. Response will be any one of two
			 * 		3.1 Found user and return with a session_id
			 * 		3.2 No user found and return is empty string
			 */
			
			if (SessionService.getCurrentUserSessionId() != '') {
				/* user email address is checked in step 1 and it is OK */
				
				/**
				 * Check if password is OK
				 */
				AuthService.isPasswordCorrect(
					md5($scope.password),
					$scope.isPasswordCorrectPass,
					$scope.isPasswordCorrectFail
				);
			} else {
				$rootScope.pushPageMessage("First provide the user email address");
				$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
			}
		};

		function checkUserForLoginProcessPass (message) {
			if (message) $rootScope.pushPageMessage(message);
			/* fire event of user exists */
			$rootScope.$broadcast(AUTH_EVENTS.loginUserExists);
		}

		function checkUserForLoginProcessFail (message) {
			if (message) $rootScope.pushPageMessage(message);
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
		}
		
		$scope.isPasswordCorrectPass = function(message) {
			if (message) $rootScope.pushPageMessage(message + 'test');
			/* fire event of successful login */
			$rootScope.$broadcast(AUTH_EVENTS.loginSuccess);							
		}
		
		$scope.isPasswordCorrectFail = function(message) {
			if (message) $rootScope.pushPageMessage(message);
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);								
		}
		
		$scope.reset = function() {
			$scope.credentials.password = '';
		};
		
		$scope.reset();

		// if a session exists for current user (page was refreshed)
		// log him in again
		if (SessionService.getCurrentUserSessionId() == '') {
			$rootScope.$broadcast(AUTH_EVENTS.sessionTimeout);
		}

					
} ]);

angular.module('ajpmApp').controller('LogoutController', ['AuthService', function(AuthService) {
	AuthService.logout();			
} ]);
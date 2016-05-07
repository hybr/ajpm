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
	'$location',
	'$mdDialog', '$mdMedia',
	function($rootScope, $scope, $state, $window, AuthService, SessionService, AUTH_EVENTS, $location, $mdDialog, $mdMedia) {

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
			$scope.isAuthenticated = false;
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
				$scope.isAuthenticated = false;
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
			$scope.isAuthenticated = false;
			$state.go('login2');
		}

		function checkUserForLoginProcessFail (message) {
			if (message) $rootScope.pushPageMessage(message);
			$scope.isAuthenticated = false;
			$state.go('login1');
		}

		$scope.isPasswordCorrectPass = function(message) {
			if (message) $rootScope.pushPageMessage(message);
			$scope.isAuthenticated = true;
			$state.go('home');
		}

		$scope.isPasswordCorrectFail = function(message) {
			if (message) $rootScope.pushPageMessage(message);
			$scope.isAuthenticated = false;
			$state.go('login2');
		}

		$scope.reset = function() {
			$scope.credentials.password = '';
		};

		$scope.register = function() {
			$location.url('/join');
			$('#userLoginModelOne').dialog("close");
			$('#userLoginModelTwo').dialog("close");
		}
		$scope.forget_password = function() {
			$location.url('/forgot');
			$('#userLoginModelOne').dialog("close");
			$('#userLoginModelTwo').dialog("close");
		}
		
		$scope.reset();

		// if a session exists for current user (page was refreshed)
		// log him in again
		if (SessionService.getCurrentUserSessionId() == '') {
			$state.go('login1');
		}


} ]);

angular.module('ajpmApp').controller('LogoutController', ['$scope', 'AuthService', function($scope, AuthService) {
	AuthService.logout();
	$scope.isAuthenticated = false;
} ]);
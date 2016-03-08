'use strict';

/**
 * Controller to handle the user login
 */

angular.module('ajpmApp').controller(
		'LoginController',
		[
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
					$rootScope.clearPageMessages();
					
					// when the form step s1 is submitted
					$scope.submit_s1 = function() {
						
						$rootScope.clearPageMessages();
						
						if (!$scope.form_login_s1.$invalid) {
							
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
								$scope.checkUserForLoginProcessPass,
								$scope.checkUserForLoginProcessFail
							);
							
						} else {
							$rootScope.pushPageMessage('Invalid entries in login form');
						}
					};
					
					// when the form step s2 is submitted
					$scope.submit_s2 = function() {
						
						$rootScope.clearPageMessages();
						
						if (!$scope.form_login_s2.$invalid) {
							
							/**
							 * Create a service here
							 * 1. Encrypt as md5 of credentials.email
							 * 2. Send the encrypted credentials.email to server
							 * 3. Response will be any one of two
							 * 		3.1 Found user and return with a session_id
							 * 		3.2 No user found and return is empty string
							 */
								
							var sessionId = SessionService.getCurrentUserSessionId();
							/* For dummy table just use email, for php we will implement session id */
							sessionId = SessionService.getCurrentUserEmail();
							
							if (sessionId != '') {
								/* user email address is checked in step 1 and it is OK */
								
								/**
								 * Check if password is OK
								 */
								AuthService.isPasswordCorrect(
									sessionId, 
									$scope.password,
									$scope.isPasswordCorrectPass,
									$scope.isPasswordCorrectFail
								);
							} else {
								$rootScope.pushPageMessage("First provide the user email address");
								$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
							}
						} else {
							
							$rootScope.pushPageMessage('Invalid entries in login form');
						}
					};

					$scope.checkUserForLoginProcessPass = function(message) {
						if (message) $rootScope.pushPageMessage(message);
						/* fire event of user exists */
						$rootScope.$broadcast(AUTH_EVENTS.loginUserExists);
					}

					$scope.checkUserForLoginProcessFail = function(message) {
						if (message) $rootScope.pushPageMessage(message);
						$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
					}
					
					$scope.isPasswordCorrectPass = function(message) {
						if (message) $rootScope.pushPageMessage(message);
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
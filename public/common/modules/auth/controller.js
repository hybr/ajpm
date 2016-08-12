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
		$scope.form_login_s1 = {};
		$scope.form_login_s2 = {};
		$scope.credential = {};
		
		// when the form step s1 is submitted
		$scope.submit_s1 = function() {

			$rootScope.clearPageMessages();
			

			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credential.email
			 * 2. Send the encrypted credential.email to server
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
				$scope.credential.email_address,
				checkUserForLoginProcessPass,
				checkUserForLoginProcessFail
			);
		};

		// when the form step s2 is submitted
		$scope.submit_s2 = function() {
			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credential.email
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
					md5($scope.credential.password),
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
			$rootScope.isAuthenticated = true;
			$state.go('home');
		}

		$scope.isPasswordCorrectFail = function(message) {
			if (message) $rootScope.pushPageMessage(message);
			$scope.isAuthenticated = false;
			$state.go('login2');
		}

		$scope.reset = function() {
			$scope.credential.email_address = '';
			$scope.credential.password = '';
		};

		$scope.register = function() {
			$state.go('join');
		}

		$scope.forget_password = function() {
			$state.go('forgot');
		}
		
		// if a session exists for current user (page was refreshed)
		// log him in again
		if (SessionService.getCurrentUserSessionId() == '') {
			$state.go('login1');
		}


} ]);

angular.module('ajpmApp').controller('LogoutController', 
	['$scope', 'AuthService', function($scope, AuthService) {
	
	AuthService.logout();
	$scope.isAuthenticated = false;

} ]);

angular.module('ajpmApp').controller('JoinController',
                ['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
               function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){

                $scope.credential = {};

                $scope.addUser = function() {
                        AuthService.addUser (
                                $scope.credential,
                                $scope.addUserPass,
                                $scope.addUserFail
                        );
                };

                $scope.addUserPass = function(message) {
                        alert('Your account is created. Please login now.' + JSON.stringify(message.data.status));
                };

                $scope.addUserFail = function(message) {
                        alert('Fail to create account: ' + JSON.stringify(message.data.status));
                };

                $scope.forgotPassword = function() {
                        $state.go('forgot');
                };

                $scope.login = function() {
                        $state.go('login1');
                };

                $scope.reset = function() {
                        $scope.credential.email_address = '';
                        $scope.credential.password = '';
                };

        } ]);


angular.module('ajpmApp').controller('ForgotController',
		['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
		function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){

		$scope.credential = {};

		$scope.randomPassword = function(length) {
			var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
			var pass = "";
			for (var x = 0; x < length; x++) {
				var i = Math.floor(Math.random() * chars.length);
				pass += chars.charAt(i);
			}
			return pass;
		};

		$scope.sendNewPassword = function() { 
			AuthService.sendActivationEmail	(
				$scope.credential.email_address,
				$scope.sendActivationEmailPass,
				$scope.sendActivationEmailFail
			);
		};
	  
		$scope.sendActivationEmailPass = function(message) {
			alert('If ' + $scope.credential.email_address + ' is a valid email address in our users list, then you will receive an email at same address to activate new password. ');
		};

		$scope.sendActivationEmailFail = function(message) {
			alert('Fail ' + message);
		};

		$scope.register = function() {
			$state.go('join');
		};

		$scope.login = function() {
			$state.go('login1');
		};

		$scope.reset = function() {
			$scope.credential.email_address = '';
		};

	} ]);

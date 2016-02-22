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
				'AUTH_EVENTS',
				function($rootScope, $scope, $state, $window, AuthService, AUTH_EVENTS) {

					/**
					 * Initialize the user record before login form is shown
					 */
					$scope.credentials = {};
					$scope.form_login = {};
					$scope.clearPageMessages();
					
					// when the form is submitted
					$scope.submit = function() {
						$scope.clearPageMessages();
						if (!$scope.form_login.$invalid) {
							$window.sessionStorage.removeItem("userInfo");
							$scope.login($scope.credentials);
						} else {
							$scope.pushPageMessage('Invalid entries in login form')
						}
					};

					/**
					 * @function login Performs the login function, by sending a request to the
					 *           server with the Auth service
					 */
					$scope.login = function(credentials) {

						
						AuthService.login(
							credentials,
							function(data) {
								var error = false;
								var message = '';
								var userRecordFromDb = data.users[credentials.email];
								
								
								if (!error && !userRecordFromDb) {
									/* email address supplied does not exists */
									error = true;
									message = "User with " + credentials.email + " does not exists";
								}
								
								if (!error && credentials.password != userRecordFromDb.password) {
									/* password is wrong */
									error = true;
									message = "Wrong password";
								}
								
								if (!error) {
									/* set the browser session, to avoid relogin on refresh */
									$window.sessionStorage["userInfo"] = JSON.stringify(userRecordFromDb);
									
									/* delete password not to be seen  client side */
									delete userRecordFromDb.password;
									
									$scope.userRecord = userRecordFromDb;
									
									/* fire event of successful login */
									$rootScope.$broadcast(AUTH_EVENTS.loginSuccess);

									
								} else {
									$scope.pushPageMessage(message);
									
									$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
								}
 
							},
							function(data) {
								console.log(data);
							}
						);
					};

					$scope.reset = function() {
						$scope.credentials.password = '';
					};
					$scope.reset();

					// if a session exists for current user (page was refreshed)
					// log him in again
					if ($window.sessionStorage["userInfo"]) {
						$scope.login(JSON.parse($window.sessionStorage["userInfo"]));
					}

					
				} ]);
'use strict';

/**
 * @service AuthService authenticate from server side
 */

angular.module('ajpmApp').factory('AuthService', [
	'$http',
	'$rootScope',
	'$window',
	'SessionService',
	'AUTH_EVENTS',
	function($http, $rootScope, $window, SessionService, AUTH_EVENTS) {

		/**
		* @variable authService name of factory
		*/
		var authService = {};

		/**
		 * @function getUserRecord to implement the authentication from server side
		 */
		authService.checkUserForLoginProcess = function(email, passFunc, failFunc) {
			/* send md5(email) to server side and verify if user exists. */
			$http({
				method: 'POST',
				url: '/common/service.php/check_user/e',
				params: { e: email }
			}).then(function successCallback(response) {
				if (response.status != 200) {
					/* connection error with server_side */
					failFunc(response.statusText); 
				} else if (response.data.status != 'User exists') {
					/* email address supplied does not exists */
					failFunc(response.data.status); /* empty session_id */
				} else {
					/* session id that we will receive from server side */
					/* attempt to login so clear the current login if exists */
					SessionService.clearUserSession();
										
					/* user exists so save the session_id and email */
					SessionService.setCurrentUserSessionId(response.data.session_id);
					SessionService.setCurrentUserEmail(email);
											
					passFunc('User exists, please enter password now'); 
					/* session id from server side */
				}
			}, function errorCallback(response) {
				failFunc(response.statusText);
			});

		}; /* authService.doesUserExists = function(email, success, error) { */
							
							
							
		authService.isPasswordCorrect = function(passwordToCheck, passFunc, failFunc) {
			$rootScope.clearPageMessages();
			
			/* send sessionId and md5(password) to server end and verify is password OK */
			$http({
				method: 'POST',
				url: '/common/service.php/check_user/p',
				params: { 
					s: SessionService.getCurrentUserSessionId(), 
					p: passwordToCheck 
				}
			}).then(function successCallback(response) {
				if (response.status != 200) {
					/* connection error with server_side */
					failFunc(response.statusText); 
				} else if (response.data.status != 'Password OK') {
					failFunc(response.data.status); 
				} else {
					/**
					 * Add user roles in browser session
					 */
					SessionService.setCurrentUserRoles (response.data.person_record.position);
					
					passFunc('You are logged in'); 
					/* session id from server side */
				}
			}, function errorCallback(response) {
				failFunc(response.statusText);
			});
		}; /* authService.isPasswordCorrect = function(passwordToCheck, passFunc, failFunc)  */


		authService.sendActivationEmail = function(emailToSend, passFunc, failFunc) {
                        /* send sessionId and md5(password) to server end and verify is password OK */
                        $http({
                                method: 'POST',
                                url: '/common/service.php/user_password/sae',
                                params: {
                                        e: emailToSend
                                }
                        }).then(function successCallback(response) {
                                passFunc(response);
                        }, function errorCallback(response) {
                               failFunc(response);
                        });

		};

		/**
		 * If current browser has session id then user exists
		 */
		authService.isAuthenticated = function() {
			return (SessionService.getCurrentUserSessionId())
		};
		
		
		/* check if the user is authorized to access the next route */
		authService.isAuthorized = function(authorizedRoles) {
			if (!angular.isArray(authorizedRoles)) {
				authorizedRoles = [ authorizedRoles ];
			}
			return (authService.isAuthenticated() && 
					authorizedRoles.indexOf(SessionService.getCurrentUserRoles()) !== -1);
		};

		/* log out the user and broadcast the logoutSuccess event */
		authService.logout = function() {
			SessionService.clearUserSession();
			$rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
		};

		authService.addUser = function(userRecordToAdd, passFunc, failFunc) {
                        $http({
                                method: 'POST',
                                url: '/common/service.php/user_join/add',
                                params: {
                                        e: userRecordToAdd.email_address,
                                        p: md5(userRecordToAdd.password),
                                        n: userRecordToAdd.name
                                }
                        }).then(function successCallback(response) {
				if (response.data.status != 'Account created successfully') {
					failFunc(response); 
				} else {
                                	passFunc(response);
				}
                        }, function errorCallback(response) {
                               failFunc(response);
                        });
		};

		return authService;
	} ]); /* AuthService */

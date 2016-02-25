'use strict';

/**
 * @service AuthService authenticate from server side
 */

angular.module('ajpmApp').factory(
				'AuthService',
				[
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
								/* this is  dummy technique, normally here the user is returned with his data from the db */
								/* send md5(email) to server side and verify if user exists. */
								$http.get('data/users.json').success(function(data) {
									/* fake user table */
									var userRecordFromDb = data.users[email];
									
									if (!userRecordFromDb) {
										/* email address supplied does not exists */
										failFunc('User does not exists'); /* empty session_id */
									} else {
										
										/* session id we will receive from server side */
										var sessionId = '234ABC';
										
										/* attempt to login so clear the current login if exists */
										SessionService.clearUserSession();
										
										/* user exists so save the session_id and email */
										SessionService.setCurrentUserSessionId(sessionId);
										SessionService.setCurrentUserEmail(email);
										
										passFunc(''); /* session id from server side */
									}						
								}).error(function() {
									failFunc('Can not connect to server side API to verify user email'); /* error */
								}); /* $http.get('data/users.json').success/error(function(data) { */
							}; /* authService.doesUserExists = function(email, success, error) { */
							
							
							
							authService.isPasswordCorrect = function(sessionId, password, passFunc, failFunc) {
								var message = '';
								
								/* this is  dummy technique, normally here the user is returned with his data from the db */
								/* send sessionId and md5(password) to server end and verify is password OK */
								$http.get('data/users.json').success(function(data) {

									/* fake user table */
									var userRecordFromDb = data.users[sessionId];
									var passOk = true;
									
									if (!userRecordFromDb) {
										/* email address supplied does not exists */
										failFunc('User does not exists');
										passOk = false;
									} else {
										if (password == '') {
											failFunc('Password is empty');
											passOk = false;
										}										
										if (userRecordFromDb.password !== password) {
											failFunc('Password does not match');
											passOk = false;
										}
										
										if (passOk) {
											/* password OK */
											/**
											 * Add user roles in browser session
											 */
											SessionService.setCurrentUserRoles (userRecordFromDb.roles);
											
											passFunc('You are logged in')
										}
									}						
								}).error(function() {
									failFunc('Can not connect to server side API to verify user password'); /* error */
								}); /* $http.get('data/users.json').success/error(function(data) { */
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
							}

							return authService;
						} ]); /* AuthService */
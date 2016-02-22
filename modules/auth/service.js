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
						'AUTH_EVENTS',
						function($http, $rootScope, $window, AUTH_EVENTS) {

							/**
							 * @variable authService name of factory
							 */
							var authService = {};

							/**
							 * @function login to implement the authentication
							 *           from server side
							 */
							authService.login = function(inputUserRecord, successFunc, errorFunc) {
								/* this is  dummy technique, normally here the user is returned with his data from the db */
								$http.get('data/users.json').success(function(data) {
									successFunc(data);								
								}).error(function() {
									errorFunc(data);
								}); /* $http.get('data/users.json').success/error(function(data) { */
							}; /* authService.login = function(inputUserRecord, success, error) { */

							// check if the user is authenticated
							authService.isAuthenticated = function() {
								return !!$window.sessionStorage.getItem("userInfo");
							};

							
							// check if the user is authorized to access the
							// next route
							// this function can be also used on element level
							// e.g. <p
							// ng-if="isAuthorized(authorizedRoles)">show this
							// only to admins</p>
							authService.isAuthorized = function(authorizedRoles) {
								if (!angular.isArray(authorizedRoles)) {
									authorizedRoles = [ authorizedRoles ];
								}
								return (authService.isAuthenticated() && 
										authorizedRoles.indexOf($window.sessionStorage.getItem("userInfo").userRoles) !== -1);
							};

							// log out the user and broadcast the logoutSuccess
							// event
							authService.logout = function() {
								$window.sessionStorage.removeItem("userInfo");
								$rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
							}

							return authService;
						} ]); /* AuthService */
'use strict';

angular.module('ajpmApp').directive('loginDialog',
				function(AUTH_EVENTS) {
					return {
						restrict : 'A',
						template : '<div ng-if="loginDialogVisibility" ng-include="\'/modules/auth/login.html\'" ></div>',
						link : function(scope) {
							scope.loginDialogVisibility = false;
							var showDialog = function() {
								scope.loginDialogVisibility = true;
							};
							
							/*
							 * The angular subscribe for AUTH_EVENTS and fire
							 * showDialog
							 */
							console.log('directive called');
							scope.$on(AUTH_EVENTS.logoutSuccess, showDialog);
							scope.$on(AUTH_EVENTS.notAuthenticated, showDialog);
							scope.$on(AUTH_EVENTS.sessionTimeout, showDialog)
						}
					};
				});
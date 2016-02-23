'use strict';

angular.module('ajpmApp').directive('loginDialog',
				function(AUTH_EVENTS) {
					return {
						restrict : 'A',
						template : '<div ng-if="loginDialogVisibility" ng-include="\'/modules/auth/login.html\'" ></div>',
						link : function(scope, elem, attr, ctrl) {
							scope.loginDialogVisibility = false;
							var dialogId = '#userLoginModelOne';
							
							
							var showDialog = function() {
								scope.loginDialogVisibility = true;
								$(dialogId).dialog();
							};
							
							var closeDialog = function() {
								scope.loginDialogVisibility = false;
								$(dialogId).dialog("close");
							};
							
							/*
							 * The angular subscribe for AUTH_EVENTS and fire
							 * showDialog
							 */
							console.log('directive called');
							scope.$on(AUTH_EVENTS.loginSuccess, closeDialog);
							scope.$on(AUTH_EVENTS.logoutSuccess, showDialog);
							scope.$on(AUTH_EVENTS.notAuthenticated, showDialog);
							scope.$on(AUTH_EVENTS.sessionTimeout, showDialog)
						}
					};
				});
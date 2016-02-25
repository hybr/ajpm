'use strict';

angular.module('ajpmApp').directive('loginDialogOne', function(AUTH_EVENTS) {
		return {
			restrict : 'A',
			template : '<div ng-if="loginDialogOneVisibility" ng-include="\'/modules/auth/login_s1.html\'" ></div>',
			link : function(scope, elem, attr, ctrl) {
				scope.loginDialogOneVisibility = false;
				var dialogId = '#userLoginModelOne';
				
				
				var showDialog = function() {
					scope.loginDialogOneVisibility = true;
					$(dialogId).dialog({
						width: 300
					});
				};
				
				var closeDialog = function() {
					scope.loginDialogOneVisibility = false;
					$(dialogId).dialog("close");
				};
				
				/*
				 * The angular subscribe for AUTH_EVENTS and fire
				 * showDialog
				 */

				scope.$on(AUTH_EVENTS.loginUserExists, closeDialog);
				scope.$on(AUTH_EVENTS.notAuthenticated, showDialog);
				scope.$on(AUTH_EVENTS.sessionTimeout, showDialog)
			}
		};
	});

angular.module('ajpmApp').directive('loginDialogTwo', 	function(AUTH_EVENTS) {
	return {
		restrict : 'A',
		template : '<div ng-if="loginDialogTwoVisibility" ng-include="\'/modules/auth/login_s2.html\'" ></div>',
		link : function(scope, elem, attr, ctrl) {
			scope.loginDialogTwoVisibility = false;
			var dialogId = '#userLoginModelTwo';
			
			
			var showDialog = function() {
				scope.loginDialogTwoVisibility = true;
				$(dialogId).dialog({
					width: 300
				});
			};
			
			var closeDialog = function() {
				scope.loginDialogTwoVisibility = false;
				$(dialogId).dialog("close");
			};
			
			/*
			 * The angular subscribe for AUTH_EVENTS and fire
			 * showDialog
			 */

			scope.$on(AUTH_EVENTS.loginSuccess, closeDialog);
			scope.$on(AUTH_EVENTS.loginUserExists, showDialog);
		}
	};
});
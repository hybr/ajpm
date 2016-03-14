
/**
 * @controller ContactUsController to maintain contact information
 */

'use strict';

angular.module('ajpmApp').controller('ContactUsController', 
	['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
	function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){
	
	$scope.positions =  SessionService.getCurrentUserRoles();
	
	// console.log(JSON.stringify($scope.positions));
	
	
} ]);
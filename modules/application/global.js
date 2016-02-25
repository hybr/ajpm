/**
 * Contains functions that are added to the root AngularJs scope.
 */
angular.module('ajpmApp').run(['$rootScope', '$state', 'AuthService', 'AUTH_EVENTS', function($rootScope, $state, AuthService, AUTH_EVENTS) {

	/* before each state change, check if the user is logged in and authorized to move onto the next state */
	$rootScope.$on('$stateChangeStart', function(event, next) {
		
		$rootScope.nextRequestedStateName = next.name;		
		/**
		 * Find the roles assigned to activity. If no roles then guest role 
		 * is assigned if we define roles to any activity/state in router 
		 * that means it is not a public page */
		var authorizedRoles = ['guest'];
		var isPublicPage = false;
		if (next && next.data && next.data.authorizedRoles) {
			authorizedRoles = next.data.authorizedRoles;
		} else {
			/* no roles assigned in router, so this page is for public */
			isPublicPage = true;
		}

		/**
		 * For private pages broadcast events if user is not logged in or authorized.
		 * 
		 */
		if (!isPublicPage && !AuthService.isAuthorized(authorizedRoles)) {
			event.preventDefault();
			if (AuthService.isAuthenticated()) {
				/* user is not allowed */
				$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
			} else {
				/* user is not logged in */
				$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
			}
		}
	});

	/* To show current active state on menu */
	$rootScope.getClass = function(path) {
		if ($state.current.name == path) {
			return "active";
		} else {
			return "";
		}
	}

}]);
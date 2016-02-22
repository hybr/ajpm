/**
 * Contains functions that are added to the root AngularJs scope.
 */
angular.module('ajpmApp').run(function($rootScope, $state, $window, AuthService, AUTH_EVENTS) {

	// before each state change, check if the user is logged in
	// and authorized to move onto the next state
	$rootScope.$on('$stateChangeStart', function(event, next) {

		
		$rootScope.currentState = next;
		
		console.log("Next ", $rootScope.currentState);
		console.log("userInfo ", $window.sessionStorage.getItem("userInfo"));
		
		if (next.name == "logout") {
			AuthService.logout();
		}
		
		var authorizedRoles = ['guest'];
		var isPublicPage = false;
		if (next && next.data && next.data.authorizedRoles) {
			authorizedRoles = next.data.authorizedRoles;
		} else {
			/* no roles assigned in router, so this page is for public */
			isPublicPage = true;
		}

		if (!isPublicPage && !AuthService.isAuthorized(authorizedRoles)) {
			event.preventDefault();
			if (AuthService.isAuthenticated()) {
				// user is not allowed
				$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
			} else {
				// user is not logged in
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

	$rootScope.logout = function() {
		AuthService.logout();
	};

});
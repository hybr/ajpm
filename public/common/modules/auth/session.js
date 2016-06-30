'use strict';

angular.module('ajpmApp').service('SessionService', [ '$window', function($window) {

	/**
	 * User information has following values session_id email roles
	 */

	var sessionService = {};

	sessionService.setSession = function(key, value) {
		$window.sessionStorage[key] = value;
	}

	sessionService.getSession = function(key) {
		return $window.sessionStorage.getItem(key);
	}

	sessionService.clearUserSession = function() {
		$window.sessionStorage.removeItem("session_id");
		$window.sessionStorage.removeItem("email");
		$window.sessionStorage.removeItem("roles");
	}

	sessionService.setCurrentUserEmail = function(value) {
		$window.sessionStorage['email'] = value;
	}

	sessionService.getCurrentUserEmail = function() {
		return $window.sessionStorage.getItem("email");
	}

	sessionService.setCurrentUserSessionId = function(value) {
		$window.sessionStorage['session_id'] = value;
	}

	sessionService.getCurrentUserSessionId = function() {
		return $window.sessionStorage.getItem("session_id");
	}

	sessionService.setCurrentUserRoles = function(value) {
		if (!angular.isArray(value)) {
			value = [ value ];
		}				
		$window.sessionStorage['roles'] = value;
	}

	sessionService.getCurrentUserRoles = function() {
		return $window.sessionStorage.getItem("roles");
	}

	sessionService.getCurrentUser = function() {
		return {
			"session_id" : $window.sessionStorage.getItem("session_id"),
			"email" : $window.sessionStorage.getItem("email"),
			"roles" : $window.sessionStorage.getItem("roles")
		};
	}
	return sessionService;

} ]);

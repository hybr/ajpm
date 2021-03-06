/**
 * @variable ajpmApp is used to hold the main angular module Main application
 *           controller ApplicationController is defined in login.js as it
 *           needed lot of other information from login controller
 */
var ajpmApp = angular.module('ajpmApp', [ 'ui.router', 'ngMaterial', 'ngMessages' ]);

ajpmApp.config([ '$httpProvider', function($httpProvider) {
	$httpProvider.defaults.useXDomain = true;
	delete $httpProvider.defaults.headers.common['X-Requested-With'];
} ]);

/**
 * authorization events
 */
ajpmApp.constant('AUTH_EVENTS', {
	loginSuccess : 'auth-login-success',
	loginFailed : 'auth-login-failed',
	loginUserExists : 'login-user-exists',
	logoutSuccess : 'auth-logout-success',
	sessionTimeout : 'auth-session-timeout',
	notAuthenticated : 'auth-not-authenticated',
	notAuthorized : 'auth-not-authorized'
});

/**
 * User roles
 */
ajpmApp.constant('USER_ROLES', {
	all : '*',
	admin : 'admin',
	editor : 'editor',
	guest : 'guest'
});

/**
 * Filter to show html as trusted html
 */
angular.module('ajpmApp').filter('showAsHtml', function($sce) {
	return function(htmlText) {
		return $sce.trustAsHtml(htmlText);
	}
});

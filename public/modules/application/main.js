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

ajpmApp.config(function($mdThemingProvider) {
	$mdThemingProvider.theme('newTheme').primaryPalette('indigo');
	$mdThemingProvider.theme('newTheme').accentPalette('brown');
	$mdThemingProvider.theme('newTheme').warnPalette('orange');
	$mdThemingProvider.theme('newTheme').backgroundPalette('amber');
	
	$mdThemingProvider.setDefaultTheme('newTheme');
});

ajpmApp.config(function($mdIconProvider) {
	$mdIconProvider.iconSet('social', 'img/icons/sets/social-icons.svg', 24)
			.defaultIconSet('img/icons/sets/core-icons.svg', 24);
});

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

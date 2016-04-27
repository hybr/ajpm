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
	var colors = _theme_2.split("_");
	if(typeof colors[0] === 'undefined') colors[0] = 'indigo';
	if(typeof colors[1] === 'undefined') colors[1] = 'brown';
	if(typeof colors[2] === 'undefined') colors[2] = 'orange';
	if(typeof colors[3] === 'undefined') colors[3] = 'amber';
	if(typeof colors[4] === 'undefined') colors[4] = '';
	$mdThemingProvider.theme('newTheme').primaryPalette(colors[0]);
	$mdThemingProvider.theme('newTheme').accentPalette(colors[1]);
	$mdThemingProvider.theme('newTheme').warnPalette(colors[2]);
	$mdThemingProvider.theme('newTheme').backgroundPalette(colors[3]);
	if (colors[4] == 'dark') {
		$mdThemingProvider.theme('newTheme').dark();
	}
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

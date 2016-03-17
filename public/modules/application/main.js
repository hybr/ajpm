/**
 * jQuery calls for look and feel
 */
$(function() {
	$("button").button();
	$("#topMenu").buttonset();
	$( ".dialog" ).dialog();
});


/**
 * @variable _0g used to hold the domain of website
 * 
 * For testing a perticular domain set this variable value to that domain
 * if you are at localhost that domain will be used to show the website
 */
var _0g = "";

_0g = "owebp.com";
_0g = "entechintl.com";
_0g = "et.owebp.com";
_0g = "farm.hybr.in";
_0g = "farm.hybr.owebp.com";
_0g = "pkmishra.owebp.com";
_0g = "kriya.owebp.com";
_0g = "apdds.owebp.com";
_0g = "syspro.owebp.com";
_0g = "ji.owebp.com";

/**
 * @function _1g to get the domain name of from the url
 * @return {string} the domain name from url
 */
var _1g = function () {
        var _1l = window.location.hostname.replace(/www\./g, "");
        if (_1l == 'localhost') {_1l = _0g;}
        if (_1l == '10.92.74.10') {_1l = _0g;}
        return _1l;
};

/**
 * @variable _2g is used to hold the person id from the person table of database
 * 
 * this is used to perform some testing
 */
var _2g = ""; /* person id */
_2g = "540d7572e4b0b539bd017122"; /* guest */
_2g = "540d90cee4b0b539bd0171c1"; /* yogesh */

/**
 * @function isInit to check if a number is integer or not
 * @param n
 * @returns {Boolean} return true if number is integer
 */
function isInt(n){
    return typeof n == "number" && isFinite(n) && n % 1 === 0;
}

/**
 * @function _3g is used to hold the server side service link
 * @return {string} the domain name from url
 */
function _3gf () {
	var _1l = window.location.hostname.replace(/www\./g, "");
	var _2l = false; /* _2l = development environment */
	if (_1l == 'localhost') {_1l = 'localhost:8000';  _2l = true;}
	if (_1l == '10.92.74.10') {_1l = 'localhost:8000'; _2l = true;}	
	if (!_2l) {
	  _1l = 'service.' . _1l;
	  _1l = 'service.ji.owebp.com';
	}
	return _1l;
}
var _3g = _3gf();


_3g  = 'service.ji.owebp.com';

/**
 * @variable ajpmApp is used to hold the main angular module Main application
 *           controller ApplicationController is defined in login.js as it
 *           needed lot of other information from login controller
 */
var ajpmApp = angular.module('ajpmApp', [ 'ui.router' ]);

ajpmApp.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }
]);

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
angular.module('ajpmApp').filter('showAsHtml', function ($sce) {
    return function (htmlText) {
    	return $sce.trustAsHtml(htmlText);
    }
});
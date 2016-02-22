/**
 * jQuery calls for look and feel
 */
$(function() {
	$("button").button();
	$("#topMenu").buttonset();
	$( ".dialog" ).dialog();
});



/**
 * @variable ajpmApp is used to hold the main angular module Main application
 *           controller ApplicationController is defined in login.js as it
 *           needed lot of other information from login controller
 */
var ajpmApp = angular.module('ajpmApp', [ 'ui.router' ]);


/**
 * authorization events
 */
ajpmApp.constant('AUTH_EVENTS', {
	loginSuccess : 'auth-login-success',
	loginFailed : 'auth-login-failed',
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
'use strict';

/**
 * This interceptor will make sure that, after each $http request if the user
 * doesn't have access to something runs the according event, given the response
 * status codes from the server.
 */
angular.module('ajpmApp').factory(
		'AuthInterceptor',
		[ '$rootScope', '$q', 'AUTH_EVENTS',
				function($rootScope, $q, AUTH_EVENTS) {
					return {
						responseError : function(response) {
							$rootScope.$broadcast({
								401 : AUTH_EVENTS.notAuthenticated,
								403 : AUTH_EVENTS.notAuthorized,
								419 : AUTH_EVENTS.sessionTimeout,
								440 : AUTH_EVENTS.sessionTimeout
							}[response.status], response);
							return $q.reject(response);
						}
					};
				}
		]
);

/**
 *  Adding the auth interceptor here, to check every $http request
 */
angular.module('ajpmApp').config(function ($httpProvider) {
  $httpProvider.interceptors.push([
    '$injector',
    function ($injector) {
      return $injector.get('AuthInterceptor');
    }
  ]);
});

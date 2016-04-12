'use strict';

/**
 * @controller HomeController to manage home page information
 */

angular.module('ajpmApp').controller('HomeController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){
	
	$http({
		method: 'POST',
		url: '/-s-web_page/presentjson',
		params: {
			id: _hpid /* home page id */
		}
	}).then(function successCallback(response) {
		if (response.status != 200) {
			/* connection error with server_side */
			$rootScope.pushPageMessage(response);
		} else {
			$scope.doc = response.data;
			$scope.os = _orgStatement;
		}
	}, function errorCallback(response) {
		$rootScope.pushPageMessage(response);
	});
		
} ]);
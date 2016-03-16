
/**
 * @controller ContactUsController to maintain contact information
 */

'use strict';

angular.module('ajpmApp').controller('HomeController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){
	
	/* Get the web page id for the home page */

	$http({
		method: 'POST',
		url: '/service/web_page/presentjson',
		params: { 
			id: _hpid
		}
	}).then(function successCallback(response) {
		if (response.status != 200) {
			/* connection error with server_side */
			$rootScope.pushPageMessage(response);
		} else {
			$scope.doc = response.data;
		}
	}, function errorCallback(response) {
		$rootScope.pushPageMessage(response);
	});
		
} ]);
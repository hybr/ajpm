
/**
 * @controller AboutUsController to maintain about us information
 */

'use strict';

angular.module('ajpmApp').controller('AboutUsController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){
	
	/* Get the web page id for the about us page */

	$http({
		method: 'POST',
		url: '/-s-web_page/presentjson',
		params: { 
			id: _auid
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
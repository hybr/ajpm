'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('WebPageController', 
	['$scope', '$rootScope', '$http', '$stateParams',
	function($scope, $rootScope, $http, $stateParams){
	
	/* Get the web page id for the about us page */

	$http({
		method: 'POST',
		url: '/-s-web_page/presentjson',
		params: { 
			id: $stateParams.webPageId
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
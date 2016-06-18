
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
		url: '/service.php/web_page/presentjson/about_us'
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
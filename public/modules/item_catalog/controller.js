'use strict';

/**
 * @controller CatalogController to manage catalog information
 */

angular.module('ajpmApp').controller('ItemCatalogController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){

	$scope.docs = [];
	
	$scope.getCategoryTitle = function(id) {
		var key;
		if (id) {
			for (key in $scope.docs) {
				if ($scope.docs[key]._id.$id == id) {
					return 'Under ' + $scope.docs[key].category;
				}
			}
		}
		return '';
	}
	
	$http({
		method: 'POST',
		url: '/-s-item_catalog/presentjsonall'
	}).then(function successCallback(response) {
		if (response.status != 200) {
			/* connection error with server_side */
			$rootScope.pushPageMessage(response);
		} else {
			$scope.docs = response.data;
		}
	}, function errorCallback(response) {
		$rootScope.pushPageMessage(response);
	});
		
} ]);
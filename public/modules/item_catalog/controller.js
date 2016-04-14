'use strict';

/**
 * @controller ItemCatalogController to manage catalog information
 */

angular.module('ajpmApp').controller('ItemCatalogController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){

	$scope.docs = [];
	$scope.items = [];
	
	$http({
		method: 'POST',
		url: '/-s-item/presentjsonall'
	}).then(function successCallback(response) {
		if (response.status != 200) {
			/* connection error with server_side */
			$rootScope.pushPageMessage(response);
		} else {
			/* TODO reduce the items size in memory */
			/* for (key in response.data) {
				$scope.items
			} */
			$scope.items = response.data;
		}
	}, function errorCallback(response) {
		$rootScope.pushPageMessage(response);
	});
	
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
	
	$scope.getItemTitle = function(id) {
		var key;
		if (id) {
			for (key in $scope.items) {
				if ($scope.items[key]._id.$id == id) {
					return $scope.items[key].title + ' ' + $scope.items[key].type;
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
'use strict';

/**
 * @controller ItemCatalogController to manage catalog information
 */

angular.module('ajpmApp').controller('ItemCatalogController', 
	['$scope', '$rootScope', '$http', 'GetCollectionService',
	function($scope, $rootScope, $http, GetCollectionService){

	$scope.docs = [];
	$scope.items = [];
	
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


       GetCollectionService.getCollection('item', function(d1) {
                $scope.items = d1;
       });
       GetCollectionService.getCollection('item_catalog', function(d1) {
                $scope.docs = d1;
       });

	
} ]);

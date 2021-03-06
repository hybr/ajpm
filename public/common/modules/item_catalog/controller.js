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
			if (id == '' || id == undefined) { 
				return '';
			}
			var key;
			if (id) {
				for (key in $scope.docs) {
					if ($scope.docs[key]._id.$id == id) {
						return 'Under ' + $scope.docs[key].category;
					}
				}
			}
			return 'Category ' + id + ' not found';
		}
		
		$scope.getItemTitle = function(id) {
			if (id == '' || id == undefined) { 
				return '';
			}			
			var key;
			if (id) {
				for (key in $scope.items) {
					if ($scope.items[key]._id.$id == id) {
						return $scope.items[key].title + ' ' + $scope.items[key].type;
					}
				}
			}
			return 'Item ' + id + ' not found';
		}


       GetCollectionService.getCollection('item', {}, function(d1) {
                $scope.items = d1;
       });
       GetCollectionService.getCollection('item_catalog', {}, function(d1) {
                $scope.docs = d1;
       });

		$scope.setMetaTag('title', 'Products and Services');
		$scope.setMetaTag('keywords', 'Service Catalog, Product Catalog, Items, Services, Products');
		$scope.setMetaTag('description', 'List of all services offered by us');
	
} ]);

'use strict';

/**
 * @controller ItemController to manage item information
 */

angular.module('ajpmApp').controller('ItemController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){

	GetDocumentByIdService.getDocument('item', $stateParams.itemId, function(doc) {
        $scope.doc = doc;
		GetDocumentByIdService.getDocument('organization', $scope.doc.manufacturar, function(d1) {
			$scope.manufacturarName = d1.name;
	    });
		$scope.setMetaTag('title', $scope.doc.type + ' ' + $scope.doc.title);
		$scope.setMetaTag('keywords',$scope.doc.title);
		$scope.setMetaTag('description',$scope.doc.summary);
		
    });
	
} ]);
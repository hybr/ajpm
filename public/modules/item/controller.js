'use strict';

/**
 * @controller ItemController to manage item information
 */

angular.module('ajpmApp').controller('ItemController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){

	$scope.currentPhoto = 0;
	GetDocumentByIdService.getDocument('item', $stateParams.itemId, function(doc) {
        $scope.doc = doc;
		GetDocumentByIdService.getDocument('organization', $scope.doc.manufacturar, function(d1) {
			$scope.manufacturarName = d1.name;
	    });
    });
	
} ]);
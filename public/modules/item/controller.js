'use strict';

/**
 * @controller ItemController to manage item information
 */

angular.module('ajpmApp').controller('ItemController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){

	var record = [];
	
	function updateManufacturarName(requestedId) {		
		GetDocumentByIdService.getDocument('organization', requestedId).then(function() {
			$scope.manufacturarName = GetDocumentByIdService.getData('organization');
	    });
	};
		
	function updateItem(requestedId) {		
		GetDocumentByIdService.getDocument('item', requestedId).then(function() {
			$scope.doc = GetDocumentByIdService.getData('item');
			updateManufacturarName($scope.doc.manufacturar);
	    });
	};
	
	updateItem($stateParams.itemId);
	
	
} ]);
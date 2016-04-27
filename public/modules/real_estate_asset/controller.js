'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('realEstateAssetController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){
	
	/* Get the web page id for the about us page */
	GetDocumentByIdService.getDocument('real_estate_asset', $stateParams.realEstateAssetId, function(d1) {
		$scope.doc = d1;
	});
		
} ]);

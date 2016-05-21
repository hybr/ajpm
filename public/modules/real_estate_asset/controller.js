'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('realEstateAssetController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){
	

	$scope.contacts = [];

	/* Get the web page id for the about us page */
	GetDocumentByIdService.getDocument('real_estate_asset', $stateParams.realEstateAssetId, function(d1) {
		$scope.doc = d1;
		for(var i=0; i<$scope.doc.contact.length; i++) {
			var contactId = $scope.doc.contact[i].contact;
			GetDocumentByIdService.getDocument('contact', contactId, function(d2) {
				if (varNotNull(d2)) {
					$scope.contacts[i] = d2;
				}
			});
		}
	});
} ]);

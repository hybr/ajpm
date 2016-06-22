'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('RealEstateAssetController', 
	['$scope', '$stateParams', 'GetDocumentByIdService', 'GetCollectionService', '$q',
	function($scope, $stateParams, GetDocumentByIdService, GetCollectionService, $q){
	
	$scope.contacts = [];
	$scope.owners = [];

	/* Get the web page id for the about us page */
	GetDocumentByIdService.getDocument('real_estate_asset', $stateParams.realEstateAssetId, function(d1) {
		$scope.doc = d1;

		
		$scope.setMetaTag('title', $scope.doc.type);
		$scope.setMetaTag('keywords',$scope.doc.type + ' Area ' + $scope.doc.area + ' ' + $scope.doc.area_unit);
		
		var contactPromisses = [];
		angular.forEach( $scope.doc.contact, function(value){
			contactPromisses.push(
				GetDocumentByIdService.getDocumentPromise('contact',value.contact)
			);
		});
		$q.all(contactPromisses).then(function(results) {
			$scope.contacts = results;
		});

		var personPromisses = [];
		angular.forEach( $scope.doc.owner, function(value){
			personPromisses.push(
				GetDocumentByIdService.getDocumentPromise('person',value.name)
			);
		});
		$q.all(personPromisses).then(function(results) {
			$scope.owners = results;
		});
	});
} ]);

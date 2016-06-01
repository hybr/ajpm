'use strict';

/**
 * @controller ItemController to manage item information
 */

angular.module('ajpmApp').controller('ActivitiesController', 
	['$scope', '$stateParams', 'GetCollectionService',
	function($scope, $stateParams, GetCollectionService){
		$scope.docs = [];
		GetCollectionService.getCollection('activity', {}, function(d1) {
			$scope.docs = d1;
		});
} ]);


angular.module('ajpmApp').controller('ActivityController', 
		['$scope', '$stateParams', 'GetCollectionService', 'GetDocumentByIdService',
		function($scope, $stateParams, GetCollectionService, GetDocumentByIdService){
			$scope.doc = [];
			$scope.fields = {};

			GetDocumentByIdService.getDocument('activity', $stateParams.activityId, function(d2) {
				$scope.doc = d2;
				/* read the detail of field in fields list */
				GetCollectionService.getCollection('database_collection_field', {id : $scope.fields}, function(d1) {
					angular.forEach($scope.doc.step, function(step) {
						angular.forEach(step.field, function(field) {
							angular.forEach(d1, function(fieldValue) {
								if (fieldValue._id.$id == field.name) {
									$scope.fields[field.name] = fieldValue;
								}
							});
						}); /* doc.step */
					}); /* doc.step */
				});
			});	
} ]);
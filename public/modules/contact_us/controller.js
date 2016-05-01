
/**
 * @controller ContactUsController to maintain contact information
 */

'use strict';
	
angular.module('ajpmApp').controller('ContactUsController', 
		['$scope', '$rootScope', '$http', 'GetCollectionService',
		function($scope, $rootScope, $http, GetCollectionService){
			
		
		GetCollectionService.getCollection('contact', function(d1) {
			$scope.docs = d1;
		});

} ]);
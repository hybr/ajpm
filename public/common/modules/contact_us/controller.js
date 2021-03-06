
/**
 * @controller ContactUsController to maintain contact information
 */

'use strict';
	
angular.module('ajpmApp').controller('ContactUsController', 
		['$scope', '$rootScope', '$http', 'GetCollectionService',
		function($scope, $rootScope, $http, GetCollectionService){
			
		
			/* model container for enquery form */
			$scope.ef = {};
			
			GetCollectionService.getCollection('contact', {}, function(d1) {
				$scope.docs = d1;
			});
			
			$scope.setMetaTag('title', 'Our Contact Information');
			$scope.setMetaTag('keywords', 'Our Contact Information, Company, Organization, Contact');
			$scope.setMetaTag('description', 'List of all phone numbers and postal addresses');			

} ]);

angular.module('ajpmApp').directive("printContact", function() {
	var d = {};

	d.restrict = 'E'; 

	d.templateUrl = '/common/modules/contact_us/print_contact.html';

	d.scope = {
		paramContact : "=argContact"
	};

	return d;
});


/**
 * @controller ContactUsController to maintain contact information
 */

'use strict';
	
angular.module('ajpmApp').controller('PersonController', 
		['$scope', '$rootScope', '$http', 'GetCollectionService',
		function($scope, $rootScope, $http, GetCollectionService){
			
		
		GetCollectionService.getCollection('person', {}, function(d1) {
			$scope.docs = d1;
		});

} ]);

angular.module('ajpmApp').directive("printPerson", function() {
	var d = {};

	d.restrict = 'E'; 

	d.templateUrl = '/modules/my_account/print_person.html';

	d.scope = {
		paramPerson : "=argPerson"
	};

	return d;
});

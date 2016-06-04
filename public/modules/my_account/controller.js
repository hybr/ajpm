
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

angular.module('ajpmApp').directive("printPersonById", function(GetDocumentByIdService) {
	var d = {};

	d.restrict = 'E'; 

	d.templateUrl = '/modules/my_account/print_person.html';

	d.scope = {
		paramPersonId : "=argPersonId"
	};

	d.link = function (scope, iElement, iAttr, ctrls, transcludeFn) {
		GetDocumentByIdService.getDocument('person', iAttr.argPersonId, function(doc) {
	       scope.paramPerson = doc;
	    });
	};
	
	return d;
});
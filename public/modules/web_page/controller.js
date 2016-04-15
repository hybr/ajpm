'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('WebPageController', 
	['$scope', '$rootScope', '$http', '$stateParams',
	function($scope, $rootScope, $http, $stateParams){
	
	/* Get the web page id for the about us page */
	GetDocumentByIdService.getDocument('web_page', $stateParams.webPageId, function(d1) {
		$scope.doc
	});
		
} ]);
'use strict';

/**
 * @controller WebPageController to nabage web page information
 */

angular.module('ajpmApp').controller('WebPageController', 
	['$scope', '$stateParams', 'GetDocumentByIdService',
	function($scope, $stateParams, GetDocumentByIdService){

	$scope.currentSliderImage = 0;
		
	/* Get the web page id for the about us page */
	GetDocumentByIdService.getDocument('web_page', $stateParams.webPageId, function(d1) {
		$scope.doc = d1;
		
		$scope.setMetaTag('title', $scope.doc.title);
		
		if (getJsonItemLength($scope.doc.web_page_keywords) > 0) {
			var keywords = '';
			for(i=0; i<= getJsonItemLength($scope.doc.web_page_keywords); i++) {
				keywords = keywords + $scope.doc.web_page_keywords[i]['word'] + ',';
			}
			$scope.setMetaTag('keywords', keywords);
		}
	});
	

} ]);
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
	});
	

} ]);

angular.module('ajpmApp').directive("printImageSlider", function() {
	var d = {};

	d.restrict = 'E'; 

	d.templateUrl = '/modules/web_page/print_image_slider.html';

	d.scope = {
			paramSliderImageRecords : "=argSliderImageRecords"
	};

	return d;
});
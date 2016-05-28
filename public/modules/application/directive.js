
/**
 * Directive to solve the form autofill data
 */
angular.module('ajpmApp').directive('formAutofillFix', 	function($timeout) {
	return function(scope, element, attrs) {
		element.prop('method', 'post');
		if (attrs.ngSubmit) {
			$timeout(function() {
				element.unbind('submit').bind(
						'submit',
						function(event) {
							event.preventDefault();
							element.find('input, textarea, select')
									.trigger('input').trigger('change')
									.trigger('keydown');
							scope.$apply(attrs.ngSubmit);
						});
			});
		}
	};
});


angular.module('ajpmApp').directive("printImageSlider", function() {
	var d = {};

	d.restrict = 'E'; 

	d.templateUrl = '/modules/web_page/print_image_slider.html';

	d.scope = {
			paramSliderImageRecords : "=argSliderImageRecords"
	};
	
	/* counter management functions */
	d.increaseCounter = function(counter, limit, step) {
		if ((counter+step) <= limit) counter = counter + step;
		return counter;
	}
	
	d.decreaseCounter = function(counter, limit, step) {
		if ((counter-step) >= limit) counter = counter - step;
		return counter;
	}		

	return d;
});

angular.module('ajpmApp').directive("printPanel", function() {
	var d = {};

	d.restrict = 'E';
	
	d.transclude = true;

	d.templateUrl = '/modules/web_page/print_panel.html';

	d.scope = {
			paramTitleOne : "=argTitleOne",
			paramTitleTwo : "=argTitleTwo",
			paramTitleThree : "=argTitleThree",
			paramContentOne : "=argContentOne",
			paramContentTwo : "=argContentTwo",
			paramContentThree : "=argContentThree"
	};
	
	return d;
});

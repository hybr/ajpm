
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
	};
	
	d.decreaseCounter = function(counter, limit, step) {
		if ((counter-step) >= limit) counter = counter - step;
		return counter;
	};	

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

/*
		<print-field 
			ng-model="ef.phone_number"
			arg-read-tag="'input'"
			arg-read-tag-type="phone"
			arg-read-tag-size=""
			arg-title="'Your Phone Number'"
			arg-name="'phone_number'"
			arg-help="'Enter your phone number starting with country code'"
			arg-required
			arg-max-length="18"
			arg-min-length="10"
			arg-pattern="(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}"
			arg-pattern-format="'+### ### ### ####'"
			arg-left-icon="'phone'"
			arg-right-icon="'home'"			
		></print-field>
 */

angular.module('ajpmApp').directive("printField", function($compile) {
	var d = {};
	d.restrict = 'E'; 
	d.require = ['^form', 'ngModel'];
	d.scope = {
			paramTitle : "=argTitle",
			paramName : "=argName",
			paramHelp : "=argHelp",
			paramReadTag : "=argReadTag",
			paramPatternFormat : "=argPatternFormat",
			paramLeftIcon : "=argLeftIcon",
			paramRightIcon : "=argRightIcon",
			paramClass : "=argClass",
			paramClick : "=argClick",
			paramNgModel : "=ngModel"
	};
	d.link = function (scope, iElement, iAttr, ctrls, transcludeFn) {
		scope.form = ctrls[0];
		var ngModelCtrl = ctrls[1];
		
		if (typeof iAttr.argMaxLength !== 'undefined') {
			if (varNotNull(iAttr.argMaxLength)) {
				scope.paramMaxLength = iAttr.argMaxLength;
			} else {
				scope.paramMaxLength = 254;
			}
		} else {
			scope.paramMaxLength = 254;
		}
		
		if (typeof iAttr.argMinLength !== 'undefined') {
			if (varNotNull(iAttr.argMinLength)) {
				scope.paramMinLength = iAttr.argMinLength;
			} else {
				scope.paramMinLength = 0;
			}
		} else {
			scope.paramMinLength = 0;
		}
		
		if (typeof iAttr.argRequired !== 'undefined') {
			scope.paramRequired = 'required';
		}
		
		if (typeof iAttr.argReadTagSize !== 'undefined') {
			if (varNotNull(iAttr.argReadTagSize)) {
				scope.paramReadTagSize = iAttr.argReadTagSize;
			} else {
				if (scope.paramReadTag == 'input') {
					scope.paramReadTagSize = 20;
				} else if (scope.paramReadTag == 'select') {
					scope.paramReadTagSize = 1;
				}
			}
		} else {
			scope.paramReadTagSize = 20;
		}

		if (typeof iAttr.argPattern !== 'undefined') {
			if (varNotNull(iAttr.argPattern)) {
				scope.paramPattern = iAttr.argPattern;
			}
		}
		
		if (typeof iAttr.argReadTagType !== 'undefined') {
			if (varNotNull(iAttr.argReadTagType)) {
				scope.paramReadTagType = iAttr.argReadTagType.toLowerCase();
			} else {
				scope.paramReadTagType = "text";
			}
		} else {
			scope.paramReadTagType = "text";
		}
		
       //get the value from ngModel
       scope.paramNgModel = ngModelCtrl.$viewValue;

       //set the value of ngModel when the local date property changes
       scope.$watch('paramNgModel', function(value) {
           if(ngModelCtrl.$viewValue != value) {
        	   ngModelCtrl.$setViewValue(value);
           }
       });
	       
       //run the ng-click function
       scope.$watch('paramClick', function(value) {
           scope.value;
       });       
	};
	d.templateUrl = function(iElement, iAttr) {
		return '/modules/form/print_' + iAttr.argReadTag.toLowerCase() + '_field.html';	
	}
	return d;
});

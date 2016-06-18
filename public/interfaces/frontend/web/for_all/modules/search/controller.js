
/**
 * @controller HomeController to manage search page information
 */

'use strict';

angular.module('ajpmApp').controller('SearchController', 
	['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http){

		 $scope.$watch('searchPattern', function() {
			$http({
				method: 'POST',
				url: '/search.php',
				params: { 
					p: $scope.searchPattern,
					s: 1
				}
			}).then(function successCallback(response) {
				if (response.status != 200) {
					/* connection error with server_side */
					$rootScope.pushPageMessage(response);
				} else {
					$scope.response = response;
				}
			}, function errorCallback(response) {
				$rootScope.pushPageMessage(response);
			});		
	    }, true);
} ]);

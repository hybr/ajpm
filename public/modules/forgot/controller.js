angular.module('ajpmApp').controller('ForgotController',
	['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
	function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){

	$scope.email = "das";

	$scope.forgot = function() {
		$rootScope.clearPageMessages();
		if ($scope.email == "") {
			$rootScope.pushPageMessage("Please enter an E-Mail Address!");
		} else {

    }
  }

	$scope.reset = function() {
		$scope.email = "";
	}

} ]);

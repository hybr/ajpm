
angular.module('ajpmApp').controller('JoinController',
		['$scope', '$rootScope', 'AuthService', 'SessionService', 'AUTH_EVENTS', '$state',
		function($scope, $rootScope, AuthService, SessionService, AUTH_EVENTS, $state){

		$scope.name = "";
		$scope.email = "";
		$scope.pass = "";
		$scope.repass = "";

		$scope.registernewuser = function() {
			$rootScope.clearPageMessages();
			if ($scope.email == "") {
				$rootScope.pushPageMessage("Please enter an E-Mail Address!");
			} else {
				if ($scope.name == "") {
					$rootScope.pushPageMessage("Please enter your name!");
				} else {
					if ($scope.pass == "") {
						$rootScope.pushPageMessage("Please enter the password!");
					} else {
						if ($scope.pass != $scope.repass) {
							$rootScope.pushPageMessage("Password doesn't match!");
						} else {

							/*Logic here*/

						}
					}
				}
			}
		}

		$scope.reset = function() {
			$scope.name = "";
			$scope.email = "";
			$scope.pass = "";
			$scope.repass = "";
			$rootScope.clearPageMessages();
		}

	} ]);

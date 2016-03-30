'use strict';

/**
 * Controller to handle the user login
 */

angular.module('ajpmApp').controller('LoginController',	[
	'$rootScope',
	'$scope',
	'$state',
	'$window',
	'AuthService',
	'SessionService',
	'AUTH_EVENTS',
	'$location',
	function($rootScope, $scope, $state, $window, AuthService, SessionService, AUTH_EVENTS, $location) {

		/**
		 * Initialize the user record before login form is shown
		 */
		$scope.credentials = {};
		$scope.form_login_s1 = {};
		$scope.form_login_s2 = {};

		// when the form step s1 is submitted
		$scope.submit_s1 = function() {

			$rootScope.clearPageMessages();


			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credentials.email
			 * 2. Send the encrypted credentials.email to server
			 * 3. Response will be any one of two
			 * 		3.1 Found user and return with a session_id
			 * 		3.2 No user found and return is empty string
			 */

			/**
			 * If user exists then we will get a valid session id to start the session from the server side
			 * if session_id is empty then user does not exists
			 */
			AuthService.checkUserForLoginProcess(
				$scope.email_address,
				checkUserForLoginProcessPass,
				checkUserForLoginProcessFail
			);
		};

		// when the form step s2 is submitted
		$scope.submit_s2 = function() {
			/**
			 * Create a service here
			 * 1. Encrypt as md5 of credentials.email
			 * 2. Send the encrypted credentials.email to server
			 * 3. Response will be any one of two
			 * 		3.1 Found user and return with a session_id
			 * 		3.2 No user found and return is empty string
			 */

			if (SessionService.getCurrentUserSessionId() != '') {
				/* user email address is checked in step 1 and it is OK */

				/**
				 * Check if password is OK
				 */
				AuthService.isPasswordCorrect(
					md5($scope.password),
					$scope.isPasswordCorrectPass,
					$scope.isPasswordCorrectFail
				);
			} else {
				$rootScope.pushPageMessage("First provide the user email address");
				$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
			}
		};

		function checkUserForLoginProcessPass (message) {
			if (message) $rootScope.pushPageMessage(message);
			/* fire event of user exists */
			$rootScope.$broadcast(AUTH_EVENTS.loginUserExists);
		}

		function checkUserForLoginProcessFail (message) {
			if (message) $rootScope.pushPageMessage(message);
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
		}

		$scope.isPasswordCorrectPass = function(message) {
			if (message) $rootScope.pushPageMessage(message + 'test');
			/* fire event of successful login */
			$rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
		}

		$scope.isPasswordCorrectFail = function(message) {
			if (message) $rootScope.pushPageMessage(message);
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
		}

		$scope.reset = function() {
			$scope.credentials.password = '';
		};

		$scope.register = function() {
						$location.url('/join');
						$('#userLoginModelOne').dialog("close");
						$('#userLoginModelTwo').dialog("close");
		}
		$scope.forget_password = function() {
						$location.url('/forgot');
						$('#userLoginModelOne').dialog("close");
						$('#userLoginModelTwo').dialog("close");
		}
		$scope.reset();

		// if a session exists for current user (page was refreshed)
		// log him in again
		if (SessionService.getCurrentUserSessionId() == '') {
			$rootScope.$broadcast(AUTH_EVENTS.sessionTimeout);
		}


} ]);

angular.module('ajpmApp').controller('LogoutController', ['AuthService', function(AuthService) {
	AuthService.logout();
} ]);
angular.module('ajpmApp').controller('searchController', ['$scope','$http', function($scope,$http) {
	$scope.areaUnit = ["sq ft", "sq yard", "sq m", "sq km", "sq mile"];
	$scope.selectUnit = $scope.areaUnit[0];
	$scope.landType = ["Home", "Bunglow", "Flat", "Agricultural", "Commercial", "Residential", "Shop", "Building", "Mall"];
	$scope.selectType = $scope.landType[0];
	$scope.accessory = ["None", "Air Conditioner", "Air Heater", "Fan", "Tubelight", "CFL Bulb", "LED Bulb", "Cooler", "Cable Box", "Cabinets", "Washing Machine", "Dish Washer", "Chimney", "Boundary Wall", "Wire Fence", "Borewell", "Well", "Electric Meter", "Water Meter", "Pipeline Cooking Gas"];
	$scope.selectAccessory = $scope.accessory[0];
	$scope.roomType = ["Bedroom", "Hall", "Kitchen", "Master Bedroom", "Bathroom", "Toilet", "Utility", "Front Yard", "Back Yard", "Balcony", "Shade"];
	$scope.selectRoomType = $scope.roomType[0];
	$scope.roomCount = 0;
	$scope.locationa = "";
	$scope.area = "";
	$scope.accessoryBuffer = [];
	$scope.accessoryOn = 0;
	$scope.roomBuffer = [];
	$scope.searchBuffer = [];
	//$scope.accessoryBuffer.push($scope.selectAccessory);
	$scope.removeAccessory = function($index) {
		$scope.accessoryBuffer.splice($index,1);
		if ($scope.accessoryBuffer.length == 0) {
			$scope.accessoryOn = 0;
		}
		document.getElementById('#accessory').reload(true);
	};
	$scope.addRoom = function() {

	};
	$scope.addAccessory = function() {
		if ($scope.accessoryOn == 0) {
			$scope.accessoryOn = 1;
		}
		$scope.accessoryBuffer.push($scope.selectAccessory);
		document.getElementById('#accessory').reload(true);
	};
	$scope.search = function() {
		$scope.varURL = "/search.php?location=" + $scope.locationa + "&area=" + $scope.area + "&areaunit=" + $scope.selectUnit + "&landtype=" + $scope.selectType + "&accessories=" + $scope.selectAccessory + "&rooms=" + $scope.rooms;
		$http({
	  method: 'GET',
	  url: $scope.varURL
	      }).then(function successCallback(response) {
	        $scope.varResponse = response.data;
	      }, function errorCallback(response) {
	        $scope.varResponse = "ERROR - " + response.error;
	      });
		$scope.searchResult = $scope.varResponse;
	}
} ]);

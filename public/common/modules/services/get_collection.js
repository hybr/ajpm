'use strict';

/**
 * @service GetCollectionService to fetch a record/document from mongo db */

angular.module('ajpmApp').service('GetCollectionService', 
	['$http', '$q', function( $http, $q) {

	this.getCollection = function(collectionName, parametersObject, callbackFunc) {
		$http({
			method: "POST",
			url: '/common/service.php/' + collectionName + '/presentjsonall',
			params: parametersObject
		}).success(function (d) {
			callbackFunc(d);
		});
	} /* getCollection */

} ]); /* function */

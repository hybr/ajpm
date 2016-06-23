'use strict';

/**
 * @service GetDocumentByIdService to fetch a record/document from mongo db */

angular.module('ajpmApp').service('GetDocumentByConditionService', ['$http', '$q', function( $http, $q) {

	this.getDocument = function(collectionName, conditionObj, callbackFunc) {
		$http({
			method: 'POST',
			url: '/common/service.php/' + collectionName + '/presentjson',
			params: { 
				condition: conditionObj
			}			
		}).success(function (d) {
			callbackFunc(d);
		});
	}; /* getDocument */

	this.getDocumentPromise = function(collectionName, conditionObj ) {
		var deferred = $q.defer();

		$http({
			method: 'POST',
			url: '/common/service.php/' + collectionName + '/presentjson',
			params: { 
				condition: conditionObj
			}			
		}).then(deferred.resolve, deferred.reject);

		return deferred.promise;

	}; /* getDocumentPromise */

} ]); /* service  */

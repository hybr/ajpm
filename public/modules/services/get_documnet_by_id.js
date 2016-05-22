'use strict';

/**
 * @service GetDocumentByIdService to fetch a record/document from mongo db */

angular.module('ajpmApp').service('GetDocumentByIdService', ['$http', '$q', function( $http, $q) {

	this.getDocument = function(collectionName, requestedId, callbackFunc) {
		$http({
			method: 'POST',
			url: '/-s-' + collectionName + '/presentjson',
			params: { 
				id: requestedId
			}			
		}).success(function (d) {
			callbackFunc(d);
		});
	} /* getDocument */

	this.getDocumentPromise = function(collectionName, requestedId ) {
		var deferred = $q.defer();

		$http({
			method: 'POST',
			url: '/-s-' + collectionName + '/presentjson',
			params: { 
				id: requestedId
			}			
		}).then(deferred.resolve, deferred.reject);

		return deferred.promise;

	} /* getDocumentPromise */

} ]); /* service  */

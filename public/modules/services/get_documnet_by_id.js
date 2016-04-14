'use strict';

/**
 * @factory GetDocumentByIdService to fetch a record/document from mongo db */

angular.module('ajpmApp').factory('GetDocumentByIdService', 
	['$http', '$q', function( $http, $q) {

	var deffered = $q.defer();
	var data = [];  
	var factory = {}; 

    factory.getDocument = function(collectionName, requestedId) {
    	$http({
			method: 'POST',
			url: '/-s-' + collectionName + '/presentjson',
			params: { 
				id: requestedId
			}			
		}).success(function (d) {
	      data[collectionName] = d;
	      console.log('GetDocumentByIdService getDocument', collectionName, requestedId, d);
	      deffered.resolve();
	    });
	    
	    return deffered.promise;
	} /* getDocument */
 
    factory.getData = function(collectionName) {
    	console.log('GetDocumentByIdService getData', collectionName, data[collectionName]);
    	return data[collectionName]; 
    };
    
    return factory;

} ]); /* function */
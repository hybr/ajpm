'use strict';

/**
 * @service GetCollectionService to fetch a record/document from mongo db */

angular.module('ajpmApp').service('GetCollectionService', 
	['$http', '$q', function( $http, $q) {

    this.getCollection = function(collectionName, callbackFunc) {
    	$http({
		method: 'POST',
		url: '/-s-' + collectionName + '/presentjsonall'
	}).success(function (d) {
		callbackFunc(d);
	    });
	} /* getCollection */

} ]); /* function */

(function (angular) {

    'use strict';

    angular.module('core')
        .factory('searchLogFactory', searchLogFactory);
    searchLogFactory.$inject = ['searchLogService', '$http', '$q'];

    function searchLogFactory(searchLogService, $http, $q) {

        return {
            getById: getById,
            storeAccounts: storeAccounts,
            storeAdvs: storeAdvs,
            storeInvoices: storeInvoices,
        };

        function getById(id) {
            var deferred = $q.defer();
            $http.get('/api/search/' + id).then(function (response) {
                deferred.resolve(new searchLogService(response.data.search));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function storeAccounts(query) {
            var deferred = $q.defer();
            $http.post('/api/search/accounts',{query:query}).then(function (response) {
                deferred.resolve( new searchLogService(response.data) );
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }
        function storeAdvs(query) {
            var deferred = $q.defer();
            $http.post('/api/search/advs',{query:query}).then(function (response) {
                deferred.resolve( new searchLogService(response.data) );
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function storeInvoices(query) {
            var deferred = $q.defer();
            $http.post('/api/search/invoices',{query:query}).then(function (response) {
                deferred.resolve( new searchLogService(response.data) );
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }


    }


})(angular);




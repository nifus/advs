(function (angular) {

    'use strict';

    angular.module('core')
        .factory('searchLogFactory', searchLogFactory);
    searchLogFactory.$inject = ['searchLogService', '$http', '$q'];

    function searchLogFactory(searchLogService, $http, $q) {

        return {
            getById: getById,
            storeAccounts: storeAccounts,
            storeUsers: storeUsers,
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
        function storeUsers(query) {
            var deferred = $q.defer();
            $http.post('/api/search/users',{query:query}).then(function (response) {
                deferred.resolve( new searchLogService(response.data) );
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }


    }


})(angular);




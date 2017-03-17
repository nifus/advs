(function (angular, window) {
    'use strict';
    angular.module('core').service('searchLogService', searchLogService);
    searchLogService.$inject = ['$http', '$q','userService','advService'];

    function searchLogService($http, $q, userService, advService) {
        return function (data) {
            var Object = data;
            Object.waiting = false;

            Object.updateQuery = function (data) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id + '/query-update', data).then(function (response) {
                    Object.waiting = false;
                    for (var i in response.data.search) {
                        Object[i] = response.data.search[i]
                    }
                    deferred.resolve();
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };

            Object.updateConfig = function (data) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id + '/config-update', {config:data}).then(function (response) {
                    Object.waiting = false;
                    for (var i in response.data.search) {
                        Object[i] = response.data.search[i]
                    }
                    deferred.resolve();
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };

            Object.getAccountResults = function (page,per_page) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id , {page:page,per_page:per_page}).then(function (response) {
                    Object.waiting = false;
                    var accounts = []
                    for (var i in response.data.rows) {
                        accounts.push( new userService(response.data.rows[i]) ) ;
                    }
                    deferred.resolve(accounts);
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            }


            return (Object);
        };

    }
})(angular, window);


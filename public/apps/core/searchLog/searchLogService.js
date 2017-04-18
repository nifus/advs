(function (angular) {
    'use strict';
    angular.module('core').service('searchLogService', ['$http', '$q', 'userService', 'advService', 'advPaymentService', searchLogService]);


    function searchLogService($http, $q, userService, advService, advPaymentService) {
        return function (data) {
            var Object = data;
            Object.waiting = false;

            Object.updateQuery = function (data) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id + '/query-update', {query: data}).then(function (response) {
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
                $http.post('/api/search/' + Object.id + '/config-update', {config: data}).then(function (response) {
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

            Object.getAccountResults = function (page, per_page) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id, {page: page, per_page: per_page}).then(function (response) {
                    Object.waiting = false;
                    var accounts = [];
                    for (var i in response.data.rows) {
                        accounts.push(new userService(response.data.rows[i]));
                    }
                    deferred.resolve(accounts);
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };
            Object.getAdvertResult = function (page, per_page) {
                var deferred = $q.defer();
                $http.post('/api/search/' + Object.id, {page: page, per_page: per_page}).then(function (response) {
                    var adverts = [];
                    for (var i in response.data.rows) {
                        var adv = new advService(response.data.rows[i]);
                        if (adv.owner) {
                            adv.owner = new userService(adv.owner);
                        }
                        adverts.push(adv)
                    }
                    deferred.resolve({advs: adverts, search: response.data.search, city: response.data.city});
                }, function (error) {
                    deferred.reject({success: false, error: error.data});
                });
                return deferred.promise;
            };
            Object.getInvoiceResult = function (page, per_page) {
                var deferred = $q.defer();
                $http.post('/api/search/' + Object.id, {page: page, per_page: per_page}).then(function (response) {
                    var adverts = [];
                    for (var i in response.data.rows) {
                        var adv = new advPaymentService(response.data.rows[i]);
                        adverts.push(adv)
                    }
                    deferred.resolve({found: adverts, search: response.data.search, city: response.data.city});
                }, function (error) {
                    deferred.reject({success: false, error: error.data});
                });
                return deferred.promise;
            };

            return (Object);
        };

    }
})(angular);


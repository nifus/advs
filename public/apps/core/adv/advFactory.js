(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advFactory', advFactory);
    advFactory.$inject = ['advService', '$http', '$q', '$filter'];

    function advFactory(advService, $http, $q, $filter) {

        return {
            energy_source: [
                {id: 'Geothermal energy', value: $filter('translate')('Geothermal energy')},
                {id: 'Solar', value: $filter('translate')('Solar')},
                {id: 'Wood', value: $filter('translate')('Wood')},
                {id: 'Gas', value: $filter('translate')('Gas')},
                {id: 'Oil', value: $filter('translate')('Oil')},
                {id: 'Teleheating', value: $filter('translate')('Teleheating')},
                {id: 'Electricity', value: $filter('translate')('Electricity')},
                {id: 'Coal', value: $filter('translate')('Coal')},
                {id: 'Other', value: $filter('translate')('Other')}
            ],
            heating: [
                {id: 'Self-contained central heating', value: $filter('translate')('Self-contained central heating')},
                {id: 'Centralheating', value: $filter('translate')('Centralheating')},
                {id: 'Teleheating', value: $filter('translate')('Teleheating')},
                {id: 'Other', value: $filter('translate')('Other')}
            ],
            energy_class: [
                {id: 'Any', value: $filter('translate')('Any')},
                {id: 'A+', value: $filter('translate')('A+')},
                {id: 'A', value: $filter('translate')('A')},
                {id: 'B', value: $filter('translate')('B')},
                {id: 'C', value: $filter('translate')('C')},
                {id: 'D', value: $filter('translate')('D')},
                {id: 'E', value: $filter('translate')('E')},
                {id: 'F', value: $filter('translate')('F')},
                {id: 'G', value: $filter('translate')('G')},
                {id: 'H', value: $filter('translate')('H')}
            ],
            store: store,
            getUserAdvById: getUserAdvById,
            getByUser: getByUser,
            getById: getById,
            getWatchByUser: getWatchByUser,
            getDataSets: getDataSets,
            getResult: getResult,
            getStatistics: getStatistics,

        };

        function getStatistics() {
            var defer = $q.defer();
            $http.get('/api/adv/statistics').then(function (response) {
                defer.resolve(response.data);
            });
            return defer.promise;
        }

        function getResult(id, data) {
            var deferred = $q.defer();
            $http.post('/api/search/' + id, data).then(function (response) {
                var advs = [];
                for (var i in response.data.advs) {
                    advs.push(new advService(response.data.advs[i]))
                }
                deferred.resolve({advs: advs, search: response.data.search, city: response.data.city});
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }


        function getDataSets() {
            var deferred = $q.defer();
            $http.get('/api/adv-data-sets').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function store(data) {
            var deferred = $q.defer();
            $http.post('/api/user/advs', data).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getUserAdvById(id) {
            var deferred = $q.defer();
            $http.get('/api/user/advs/' + id).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                console.log(error);
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getById(id) {
            var deferred = $q.defer();
            $http.get('/api/advs/' + id).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getByUser() {
            return $http.put('/api/user/advs').then(function (response) {
                var objs = [];
                for (var i in response.data) {
                    objs.push(new advService(response.data[i]))
                }
                return objs;
            })
        }

        function getWatchByUser() {
            return $http.put('/api/user/watch-advs').then(function (response) {
                var objs = [];
                for (var i in response.data) {
                    objs.push(new advService(response.data[i]))
                }
                return objs;
            })
        }


    }


})(angular, window);




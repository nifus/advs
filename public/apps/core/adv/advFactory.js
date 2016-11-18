(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advFactory', advFactory);
    advFactory.$inject = ['advService', '$http', '$q'];

    function advFactory(advService, $http, $q) {

        return {
            store: store,
            getUserAdvById: getUserAdvById,
            getByUser: getByUser,
            getById: getById,
            getWatchByUser: getWatchByUser,
            getDataSets: getDataSets,
            getResult: getResult,

        };

        function getResult(id, data) {
            var deferred = $q.defer();
            $http.post('/api/search/'+id , data).then(function (response) {
                var advs = [];
                for( var i in response.data.advs){
                    advs.push( new advService(response.data.advs[i]) )
                }
                deferred.resolve({advs:advs, search:response.data.search, city:response.data.city});
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }



        function getDataSets() {
            var deferred = $q.defer();
            $http.get('/api/adv-data-sets' ).then(function (response) {
                deferred.resolve( response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function store(data) {
            var deferred = $q.defer();
            $http.post('/api/user/advs',data ).then(function (response) {
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




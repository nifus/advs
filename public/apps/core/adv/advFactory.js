(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advFactory', advFactory);
    advFactory.$inject = ['advService', '$http', '$q'];

    function advFactory(advService, $http, $q) {

        return {
            getUserAdvById: getUserAdvById,
            getByUser: getByUser,
            getWatchByUser: getWatchByUser

        };

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




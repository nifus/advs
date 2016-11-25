(function (angular, window) {
    'use strict';
    angular.module('core').service('searchLogService', searchLogService);
    searchLogService.$inject = ['$http', '$q'];

    function searchLogService($http, $q) {
        return function (data) {
            var Object = data;
            Object.waiting = false;

            Object.update = function (data) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/search/' + Object.id + '/update', data).then(function (response) {
                    Object.waiting = false;
                    for (var i in response.data.search) {
                        Object[i] = response.data.search[i]
                    }
                    deferred.resolve();
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };


            return (Object);
        };

    }
})(angular, window);


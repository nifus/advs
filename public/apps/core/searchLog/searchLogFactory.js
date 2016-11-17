(function (angular) {

    'use strict';

    angular.module('core')
        .factory('searchLogFactory', searchLogFactory);
    searchLogFactory.$inject = ['searchLogService', '$http', '$q'];

    function searchLogFactory(searchLogService, $http, $q) {

        return {
            getById: getById
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


    }


})(angular);




(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('tariffFactory', tariffFactory);
    tariffFactory.$inject = ['tariffService', '$http', '$q'];

    function tariffFactory(tariffService, $http, $q) {

        return {
            getActiveTariff: getActiveTariff,

        };

        function getActiveTariff() {
            var deferred = $q.defer();

            $http.get('/api/user/tariff').then(function (response) {
                deferred.resolve( new tariffService(response.data.tariff, response.data.tariffDetails , response.data.tariffs) );
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });

            return deferred.promise;
        }


    }


})(angular, window);




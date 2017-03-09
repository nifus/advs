(function (angular, window) {
    'use strict';
    angular.module('core').service('businessTariffService', ['$http', '$q', businessTariffService]);

    function businessTariffService($http, $q) {

        return function (data) {
            var Object = data;

            Object.update = update;

            return (Object);

            function update(data) {
                var defer = $q.defer();
                $http.post('/api/tariff/business/' + Object.id, data).then(
                    function (response) {
                        defer.resolve(response.data);
                    }, function (response) {
                        var error = response.data.error ? response.data.error : response.statusText;
                        defer.reject({error: error})
                    }
                );
                return defer.promise;
            }

        };
    }
})(angular, window);


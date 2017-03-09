(function (angular, window) {
    'use strict';
    angular.module('core').service('privateTariffService', ['$http', '$q', privateTariffService]);

    function privateTariffService($http, $q) {

        return function (data) {
            var Object = data;

            Object.update = update;

            return (Object);



        };
    }
})(angular, window);


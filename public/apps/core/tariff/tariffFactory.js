(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('tariffFactory', [ '$http', '$q', tariffFactory]);

    function tariffFactory( $http, $q) {

        return {
            getPrivatePrices: getPrivatePrices,
            getBusinessPrices: getBusinessPrices,
            getPrivateTariffs: getPrivateTariffs,
            getBusinessTariffs: getBusinessTariffs,
            updatePrivateTariffs: updatePrivateTariffs,
            updateBusinessTariffs: updateBusinessTariffs,

        };

        function getPrivatePrices() {
            var deferred = $q.defer();
            $http.get('/api/tariff/private-prices').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }
        function getBusinessPrices() {
            var deferred = $q.defer();
            $http.get('/api/tariff/business-prices').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getPrivateTariffs() {
            var deferred = $q.defer();

            $http.get('/api/tariff/private').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }
        function updatePrivateTariffs(data) {
            var defer = $q.defer();
            $http.post('/api/tariff/private', data).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }
        function getBusinessTariffs() {
            var deferred = $q.defer();

            $http.get('/api/tariff/business').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }
        function updateBusinessTariffs(data) {
            var defer = $q.defer();
            $http.post('/api/tariff/business', data).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

    }


})(angular, window);




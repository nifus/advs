(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('tariffFactory', ['$http', '$q', 'businessTariffService', tariffFactory]);

    function tariffFactory($http, $q, businessTariffService) {

        return {
            getPrivatePrices: getPrivatePrices,
            getBusinessPrices: getBusinessPrices,
            getPrivateTariffs: getPrivateTariffs,
            getBusinessTariffs: getBusinessTariffs,
            updatePrivateTariffs: updatePrivateTariffs,
            updateBusinessTariffs: updateBusinessTariffs,
            getCurrentTariff: getCurrentTariff,

            getFutureTariff: getFutureTariff,
        };


        function getCurrentTariff() {
            var deferred = $q.defer();
            $http.get('/api/tariff/current').then(function (response) {
                if (response.data.tariff==null){
                    deferred.resolve( null );
                }else{
                    deferred.resolve( new businessTariffService(response.data.tariff) );
                }
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getFutureTariff() {
            var deferred = $q.defer();
            $http.get('/api/tariff/future').then(function (response) {
                if (response.data.tariff==null){
                    deferred.resolve( null );
                }else{
                    deferred.resolve( new businessTariffService(response.data.tariff) );
                }
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

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




(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advPaymentFactory', ['$http', '$q', advPaymentFactory]);

    function advPaymentFactory($http, $q) {

        return {
            createPrePayment: createPrePayment,
            createPaypalPayment: createPaypalPayment,
            createGiroPayment: createGiroPayment,
        };


        function createPrePayment(adv_id, guid, tariff_id, price) {
            var defer = $q.defer();
            $http.post('/api/payment/pre-payment', {
                adv_id: adv_id,
                guid: guid,
                price: price,
                tariff_id: tariff_id
            }).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

        function createPaypalPayment(adv_id, email, tariff_id, price) {
            var defer = $q.defer();
            $http.post('/api/payment/paypal', {adv_id: adv_id, email: email, price: price, tariff_id: tariff_id}).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

        function createGiroPayment(adv_id, account, tariff_id, price) {
            var defer = $q.defer();
            $http.post('/api/payment/giro', {
                adv_id: adv_id,
                account: account,
                price: price,
                tariff_id: tariff_id
            }).then(
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




(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advPaymentFactory', ['$http', '$q', advPaymentFactory]);

    function advPaymentFactory($http, $q) {

        return {
            createSubscription: createSubscription,
            createSlots: createSlots,
            createAdvert: createAdvert
        };

        function createAdvert(type, advert_id, tariff_id,  options) {
            var defer = $q.defer();
            var url, data;
            if (type=='prepayment'){
                url = '/api/payment/advert/pre-payment';
                data = {
                    guid: options,
                    advert_id: advert_id,
                    tariff_id: tariff_id
                }
            }else if(type=='paypal'){
                url = '/api/payment/advert/paypal';
                data = {
                    email: options,
                    advert_id: advert_id,
                    tariff_id: tariff_id
                }
            }else if(type=='giropay'){
                url = '/api/payment/advert/giro';
                data = {
                    account: options,
                    advert_id: advert_id,
                    tariff_id: tariff_id
                }
            }


            $http.post(url, data).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }
        function createSlots(type, slots, options) {
            var defer = $q.defer();
            var url, data;
            if (type=='prepayment'){
                url = '/api/payment/slot/pre-payment';
                data = {
                    guid: options,
                    slots: slots
                }
            }else if(type=='paypal'){
                url = '/api/payment/slot/paypal';
                data = {
                    email: options,
                    slots: slots
                }
            }else if(type=='giropay'){
                url = '/api/payment/slot/giro';
                data = {
                    account: options,
                    slots: slots
                }
            }


            $http.post(url, data).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

        function createSubscription(type, tariff, options) {
            var defer = $q.defer();
            var url, data;
            if (type=='prepayment'){
                url = '/api/payment/subscription/pre-payment';
                data = {
                    guid: options,
                    price: tariff.price,
                    tariff_id: tariff.id
                }
            }else if(type=='paypal'){
                url = '/api/payment/subscription/paypal';
                data = {
                    email: options,
                    price: tariff.price,
                    tariff_id: tariff.id
                }
            }else if(type=='giropay'){
                url = '/api/payment/subscription/giro';
                data = {
                    account: options,
                    price: tariff.price,
                    tariff_id: tariff.id
                }
            }


            $http.post(url, data).then(
                function (response) {
                    defer.resolve(response.data);
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

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




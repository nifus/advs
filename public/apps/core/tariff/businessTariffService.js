(function (angular, window) {
    'use strict';
    angular.module('core').service('businessTariffService', ['$http', '$q', businessTariffService]);

    function businessTariffService($http, $q) {

        return function (data) {
            var Object = data;

            Object.update = update;
            Object.StartDate = moment(data.begin_time).format('DD.MM.Y H:m');
            Object.EndDate = moment(data.end_time).format('DD.MM.Y H:m');
            Object.LeftDays = function () {
                return moment(Object.end_time).diff(moment(), 'days');
            }();
            Object.getSlots = getSlots;
            Object.buyAdditionalSlot = buyAdditionalSlot;
            Object.endSubscription = endSubscription;
            Object.freshPackageSlots = 0;
            Object.usedPackageSlots = 0;
            Object.freshExtraSlots = 0;
            Object.usedExtraSlots = 0;
            Object.allSlots = [];

            return (Object);


            function endSubscription() {
                var defer = $q.defer();

                $http.get('/api/tariff/end').then(function (response) {
                    defer.resolve();
                }, function (error) {
                    defer.reject({success: false, error: error.data});
                });
                return defer.promise;
            }

            function buyAdditionalSlot() {
                var defer = $q.defer();

                $http.get('/api/tariff/slots').then(function (response) {
                    Object.allSlots = response.data;
                    angular.forEach(response.data, function (slot) {
                        if (slot.is_extra_slot=='1' ){
                            (slot.activate_time == null) ? Object.freshExtraSlots++ : Object.usedExtraSlots++;
                        }else{
                            (slot.activate_time == null) ? Object.freshPackageSlots++ : Object.usedPackageSlots++;
                        }

                    });

                    defer.resolve();

                }, function (error) {
                    defer.reject({success: false, error: error.data});
                });
                return defer.promise;
            }

            function getSlots() {
                var defer = $q.defer();

                $http.get('/api/tariff/slots').then(function (response) {
                    Object.allSlots = response.data;
                    angular.forEach(response.data, function (slot) {
                        if (slot.is_extra_slot=='1' ){
                            (slot.activate_time == null) ? Object.freshExtraSlots++ : Object.usedExtraSlots++;
                        }else{
                            (slot.activate_time == null) ? Object.freshPackageSlots++ : Object.usedPackageSlots++;
                        }

                    });

                    defer.resolve();

                }, function (error) {
                    defer.reject({success: false, error: error.data});
                });
                return defer.promise;
            }

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


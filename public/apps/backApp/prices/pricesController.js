(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('pricesController', ['$scope', 'configFactory', '$q', '$state', '$filter', pricesController]);


    function pricesController($scope, configFactory, $q, $state, $filter) {
        $scope.env = {};
        $scope.price = {};

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if (!$scope.user.hasPermission('prices')) {
                $state.go('prices');
                return;
            }

            var config_promise = configFactory.get().then(function (response) {
                if (response.prices) {
                    $scope.price = response.prices
                }
            });
            $q.all([config_promise]).then(function () {
                return deferred.resolve();
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

        $scope.savePrivatePrices = function () {
            configFactory.savePrivatePrices($scope.price.private).then(function (response) {
                if (response.success == true) {
                    alertify.success($filter('translate')('Prices was updated'))
                }
            })
        };
        $scope.saveBusinessPrices = function () {
            configFactory.saveBusinessPrices($scope.price.business).then(function (response) {
                if (response.success == true) {
                    alertify.success($filter('translate')('Prices was updated'))
                }
            })
        }
    }

})();
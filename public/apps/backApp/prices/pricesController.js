(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('pricesController', ['$scope', 'tariffFactory', '$q', '$state', '$filter', pricesController]);


    function pricesController($scope, tariffFactory, $q, $state, $filter) {
        $scope.env = {
            loading: true
        };
        $scope.price = {};
        $scope.users = {};

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if (!$scope.user.hasPermission('prices')) {
                $state.go('sign_in');
                return;
            }

            var private_promise = tariffFactory.getPrivateTariffs().then(function (response) {
                $scope.price.private = response.tariffs;
                $scope.users.private = response.prev_user;
            });
            var business_promise = tariffFactory.getBusinessTariffs().then(function (response) {
                $scope.price.business = response.tariffs;
                $scope.users.business = response.prev_user;

            });
            $q.all([private_promise,business_promise ]).then(function () {
                $scope.env.loading = false;
                deferred.resolve();
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

        $scope.savePrivatePrices = function () {
            tariffFactory.updatePrivateTariffs($scope.price.private).then(function (response) {
                if (response.success != false) {
                    alertify.success($filter('translate')('Prices was updated'))
                }
            })
        };
        $scope.saveBusinessPrices = function () {
            tariffFactory.updateBusinessTariffs($scope.price.business).then(function (response) {
                if (response.success != false) {
                    alertify.success($filter('translate')('Prices was updated'))
                }
            })
        }
    }

})();
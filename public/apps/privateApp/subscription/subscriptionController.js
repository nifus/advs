(function () {
    'use strict';
    angular.module('privateApp').controller('subscriptionController', subscriptionController);

    subscriptionController.$inject = ['$scope', '$filter', '$q', '$window', '$http'];

    function subscriptionController($scope, $filter, $q, $window, $http) {
        $scope.promises = null;
        $scope.env = {
            loading: true,
            submit: false,
            tariffs: [],
            tariff:null
        };
        $scope.model = {
            type_id: null
        }
        $scope.slot_form = {
            number_of: 1
        }

        function initPage(deferred) {

            $window.document.title = $filter('translate')('Subscription');

            var tariffsPromise = $http.get('/api/tariffs').then(function (response) {
                $scope.env.tariffs = response.data;
            });

            var statPromise = $scope.user.getAdvStat().then(function (result) {
                $scope.env.stat = result;
            });


            var tariffPromise = $scope.user.getTariff().then(function (tariff) {
                $scope.env.tariff = tariff;
                if ( tariff!=null ){
                    $scope.model.tariff = tariff.type_id;
                }
                console.log(tariff)
            });

            $q.all([tariffsPromise, statPromise, tariffPromise]).then(function () {
                $scope.env.loading = false;
                deferred.resolve()
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.buyTariff = function (tarif_id) {
            $scope.user.buyTariff(tarif_id).then(function (response) {
                if (response.success==true){
                    window.location.reload(true)
                }
            })
        }

        $scope.buyAdditionalSlot = function () {
            $scope.env.submit = true;
            $scope.user.buyAdditionalSlot.then(function (response) {
                if (response.success==true){
                    window.location.reload(true)
                }
            })
        }
    }
})();


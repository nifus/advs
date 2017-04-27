(function () {
    'use strict';
    angular.module('privateApp').controller('subscriptionController', subscriptionController);

    subscriptionController.$inject = ['$scope', '$filter', '$q', '$window', '$http','tariffFactory'];

    function subscriptionController($scope, $filter, $q, $window, $http, tariffFactory) {
        $scope.promises = null;
        $scope.env = {
            loading: true,
            submit: false,
            tariffs: [],
            tariff:null,
            action: 'subscription',
            selected_tariff:null,
            slots:[]
        };
        $scope.model = {
            tariff_id: null
        };
        $scope.slot_form = {
            number_of: 1
        };
        $scope.payment_form = {
            submit: false
        };

        function initPage(deferred) {

            $window.document.title = $filter('translate')('Subscription');
            $scope.payment_form.payment_type = $scope.$parent.user.payment_type;
            $scope.payment_form.paypal_email = $scope.$parent.user.paypal_email;
            $scope.payment_form.giro_account = $scope.$parent.user.giro_account;


            var statPromise = $scope.user.getAdvStat().then(function (result) {
                $scope.env.stat = result;
            });

            var tariffsPromise = tariffFactory.getBusinessPrices().then(function (response) {
                $scope.env.tariffs = response;
            });

            var currentTariffPromise = $scope.user.getCurrentTariff().then(function (response) {
                $scope.env.tariff = response;
                if ( $scope.env.tariff!=null ){
                    $scope.env.tariff.getSlots().then(function (response) {
                        $scope.env.slots = response;
                    });
                }
            });


            var futureTariffPromise = $scope.user.getFutureTariff().then(function (tariff) {
                $scope.env.future_tariff = tariff;
            });

            $q.all([tariffsPromise, statPromise, currentTariffPromise, futureTariffPromise, tariffsPromise]).then(function () {
                $scope.env.loading = false;
                deferred.resolve()
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.endSubscription = function () {
            $scope.env.tariff.endSubscription().then(function (response) {
                alertify.success($filter('translate')("Subscription is ended"));
                $scope.env.tariff =  angular.copy($scope.env.future_tariff);
                $scope.env.future_tariff = null;
            })
        };

        $scope.buyTariff = function () {
            $scope.env.action = 'buy_tariff';
        };
        $scope.buyAdditionalSlot = function () {
            $scope.env.action = 'buy_slot';
        };
        $scope.cancel = function () {
            $scope.env.action = 'subscription';
            $scope.env.selected_tariff = null;
        };
        $scope.buySelectedTariff = function (form) {
            $scope.payment_form.submit = true;
            if ( form.$invalid ){
                return;
            }
        };

        /*$scope.buyAdditionalSlot = function () {
            $scope.env.submit = true;
            $scope.env.tariff.buyAdditionalSlot.then(function (response) {
                if (response.success==true){
                    window.location.reload(true)
                }
            })
        }*/
    }
})();


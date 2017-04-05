(function () {
    'use strict';
    angular.module('privateApp').controller('editController', ['$scope', 'advFactory', '$q', '$state', '$filter', '$timeout', 'tariffFactory','advPaymentFactory', editController]);


    function editController($scope, advFactory, $q, $state, $filter, $timeout ,tariffFactory, advPaymentFactory) {

        var promises = [];

        $scope.env = {
            action: 'form',
            submit: false,
            id: $state.params.id,
            react: $state.params.react,
            display_form: !$state.params.react == 1,
            loading: true,

        };

        function initPage(deferred) {

            var advPromise = advFactory.getById($scope.env.id).then(function (adv) {
                $scope.model = adv;
            });
            promises.push(advPromise);

            tariffFactory.getPrivatePrices().then(function (response) {
                $scope.env.tariffs = response;
            });

            $q.all(promises).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);





        $scope.pay = function (form) {
            $scope.env.submit = true;
            if (form.$invalid) {
                return false;
            }
            if ($scope.user.payment_type == 'prepayment') {
                advPaymentFactory.createPrePayment($scope.model.id, $scope.env.guid, $scope.env.tariff.id, $scope.env.tariff.price).then(function (response) {

                })
            } else if ($scope.user.payment_type == 'paypal') {
                advPaymentFactory.createPaypalPayment($scope.model.id, $scope.user.paypal_email, $scope.env.tariff.id, $scope.env.tariff.price).then(function (payment) {
                    $('#paypalForm').attr('action', '/payment/emulation/paypal/' + payment.id);
                    $('#paypalForm').submit();
                })
            } else if ($scope.user.payment_type == 'giropay') {
                advPaymentFactory.createGiroPayment($scope.model.id, $scope.user.giro_account, $scope.env.tariff.id, $scope.env.tariff.price).then(function (payment) {
                    $('#giroForm').attr('action', '/payment/emulation/giro/' + payment.id);
                    $('#giroForm').submit();
                })
            }
            return false;
        };
        $scope.setPrivateTariff = function (tariff) {
            $scope.env.tariff = tariff;
            $scope.env.tariff.price = ($scope.model.type == 'rent') ? tariff.rent_price : tariff.sale_price;
            $scope.env.tariff.begin_date = moment().format('D.MM.Y');
            if (tariff.duration == '1 week') {
                $scope.env.tariff.end_date = moment().add(7, 'days').format('D.MM.Y')
            } else if (tariff.duration == '2 weeks') {
                $scope.env.tariff.end_date = moment().add(14, 'days').format('D.MM.Y')
            } else if (tariff.duration == '1 month') {
                $scope.env.tariff.end_date = moment().add(1, 'months').format('D.MM.Y')
            } else if (tariff.duration == '2 months') {
                $scope.env.tariff.end_date = moment().add(2, 'months').format('D.MM.Y')
            } else if (tariff.duration == '3 months') {
                $scope.env.tariff.end_date = moment().add(3, 'months').format('D.MM.Y')
            }
        };
        $scope.deleteAdvert = function () {
            alertify.confirm($filter('translate')("Are you sure  want to delete this advert?"), function (e) {
                if (e) {
                    $scope.model.delete().then(function (response) {
                        localStorage.setItem("advert_id", null);
                        window.location.reload(true);

                    })
                }
            });
        };

        $scope.save = function (data) {

            $scope.model.update(data).then(function () {
                    $scope.env.send = false;
                    //$scope.model = response;
                    if ($scope.model.status == 'payment_waiting') {
                        $scope.env.action = 'payment';
                    } else {
                        alertify.success($filter('translate')("Advert changed"));
                        $timeout(function () {
                            $state.go("my-adv")
                        }, 1000);
                    }
                    $scope.env.submit = false;

                }, function (error) {
                    $scope.env.send = false;
                    $scope.env.submit = false;

                    console.log(error)
                }
            )

        };

        $scope.backToForm = function () {
            $scope.env.action = 'form';
        };
        $scope.backToPayment = function () {
            $scope.env.action = 'payment';
        };
        $scope.previewAdv = function () {
            $scope.env.action = 'preview';
        };


    }
})();


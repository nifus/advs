(function (angular) {
    'use strict';


    function paymentFormDirective($state, advPaymentFactory) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            controller: paymentFormController,
            templateUrl: '/apps/core/directives/paymentForm/paymentForm.html',
            scope: {
                user: '=',
                tariff: '=',
                price: '@',
                description: '@',
                type: '@',
                slots: '@',
                advert: '='
            }
        };


        function paymentFormController($scope) {
            $scope.env = {
                guid: guid(null),
                submit: false
            };

            $scope.pay = function (form) {
                $scope.env.submit = true;
                if (form.$invalid) {
                    return false;
                }
                if ($scope.type == 'advert') {
                    payAdvert()
                } else if ($scope.type == 'subscription') {
                    paySubscription()
                } else if ($scope.type == 'slots') {
                    paySlots()
                }


                return false;
            };

            function paySubscription() {
                if ($scope.user.payment_type == 'prepayment') {
                    advPaymentFactory.createSubscription('prepayment', $scope.tariff, $scope.env.guid)
                        .then(function (response) {
                            $state.go('my-subscription')
                        })
                } else if ($scope.user.payment_type == 'paypal') {
                    advPaymentFactory.createSubscription('paypal', $scope.tariff, $scope.user.paypal_email)
                        .then(function (payment) {
                            $('#paypalForm').attr('action', '/payment/emulation/paypal/' + payment.id);
                            $('#paypalForm input[name=redirect]').val('/user#!/my-subscription');
                            $('#paypalForm').submit();
                        })
                } else if ($scope.user.payment_type == 'giropay') {
                    advPaymentFactory.createSubscription('giropay', $scope.tariff, $scope.user.giro_account)
                        .then(function (payment) {
                            $('#giroForm').attr('action', '/payment/emulation/giro/' + payment.id);
                            $('#giroForm input[name=redirect]').val('/user#!/my-subscription');

                            $('#giroForm').submit();
                        })
                }
            }

            function paySlots() {
                if ($scope.user.payment_type == 'prepayment') {
                    advPaymentFactory.createSlots('prepayment', $scope.slots, $scope.env.guid)
                        .then(function (response) {
                            $state.go('my-subscription')
                        })
                } else if ($scope.user.payment_type == 'paypal') {
                    advPaymentFactory.createSlots('paypal', $scope.slots, $scope.user.paypal_email)
                        .then(function (payment) {
                            $('#paypalForm').attr('action', '/payment/emulation/paypal/' + payment.id);
                            $('#paypalForm input[name=redirect]').val('/user#!/my-subscription');
                            $('#paypalForm').submit();
                        })
                } else if ($scope.user.payment_type == 'giropay') {
                    advPaymentFactory.createSlots('giropay', $scope.slots, $scope.user.giro_account)
                        .then(function (payment) {
                            $('#giroForm').attr('action', '/payment/emulation/giro/' + payment.id);
                            $('#giroForm input[name=redirect]').val('/user#!/my-subscription');
                            $('#giroForm').submit();
                        })
                }
            }

            function payAdvert() {
                if ($scope.user.payment_type == 'prepayment') {
                    advPaymentFactory.createPrePayment($scope.model.id, $scope.env.guid, $scope.env.tariff.id, $scope.env.tariff.price).then(function (response) {
                        window.location.href = '/'
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
            }
        }


        function guid(id) {
            return id + '-' + moment().format('DDMM') + '-' + moment().format('HHss')
        }


    }

    angular.module('core').directive('paymentForm', ['$state', 'advPaymentFactory', paymentFormDirective]);


})(window.angular);

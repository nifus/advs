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
        type: '@',
        slots: '@',
        advert: '='
      }
    };


    function paymentFormController($scope) {
      $scope.env = {
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
          advPaymentFactory.createSubscription('prepayment', $scope.tariff, $scope.model.guid)
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
          advPaymentFactory.createSlots('prepayment', $scope.slots, $scope.model.guid)
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
          advPaymentFactory.createAdvert('prepayment', $scope.advert.id, $scope.tariff.id, guid($scope.advert.id)).then(function (response) {
            window.location.href = '/'
          })
        } else if ($scope.user.payment_type == 'paypal') {
          advPaymentFactory.createAdvert('paypal', $scope.advert.id, $scope.tariff.id, $scope.user.paypal_email).then(function (payment) {
            $('#paypalForm').attr('action', '/payment/emulation/paypal/' + payment.id);
            $('#paypalForm').submit();
          })
        } else if ($scope.user.payment_type == 'giropay') {
          advPaymentFactory.createAdvert('giropay', $scope.advert.id, $scope.tariff.id, $scope.user.giro_account).then(function (payment) {
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

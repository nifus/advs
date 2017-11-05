(function () {
  'use strict';
  angular.module('frontApp').controller('createAdvController', ['$scope', 'advFactory', '$filter', '$timeout', '$q', 'tariffFactory', 'advPaymentFactory', createAdvController]);


  function createAdvController($scope, advFactory, $filter, $timeout, $q, tariffFactory, advPaymentFactory) {


    $scope.env = {
      display_preview: false,
      submit: false,
      advert: null,
      restore_flag: false,
      loading: true,
      action: 'form',
      tariffs: [],
      tariff: null,
    };

    var promises = [];
    var default_model = {
          type: 'rent',
          category: 1,
          subcategory: 'Any',
          photos: [],
          address: {
            display_house: true
          },
          energy: {
            pass: 'Available',
            pass_date: 'Till 30.04.14 (EnEV 2009)',
            pass_type: 'Consumption pass',
            class: 'Any',
            source: ''
          },
          props: {
            pets: 'Any',
            floor_cover: 'Any',
            air_conditioner: 'By agreement',
            recommended_usage: 'Any'
          },
          finance: {
            ancillary_cost_included: 1,
          },
          author: {},
          air_conditioner: 'By agreement',
          edp_cabling: 'By agreement',
          price_type: 'Price per month',
          development: 'Developed',
          building_permission: 'Yes',
          equipments:[]
        };
    $scope.model = default_model;

    function initPage(deferred) {
      $scope.user = $scope.$parent.env.user;

      $scope.model.author.sex = $scope.user.sex;
      $scope.model.author.name = $scope.user.name;
      $scope.model.author.surname = $scope.user.surname;
      $scope.model.author.email = $scope.user.email;
      $scope.model.author.phone = $scope.user.phone;

      var restore_advert_id = localStorage.getItem("advert_id");
      if (restore_advert_id != null) {
        var adv_restore_promise = advFactory.restoreAdvert(restore_advert_id).then(function (response) {
          $scope.model = response;
          $scope.env.action = 'payment';
          $scope.env.restore_flag = true;
          $scope.env.guid = advFactory.guid($scope.model.id);
        }, function () {

        });
        promises.push(adv_restore_promise);
      }
      if ($scope.user.isPrivateAccount()) {
        tariffFactory.getPrivatePrices().then(function (response) {
          $scope.env.tariffs = response;
        });
      } else {
        tariffFactory.getBusinessPrices().then(function (response) {
          $scope.env.tariffs = response;

        });
        $scope.user.getCurrentTariff().then(function (response) {
          $scope.env.tariff = response;
          //if ( response ){
          //    $scope.env.tariff.getSlots()
          // }
          console.log(response)
        })
      }


      $q.all(promises).then(function () {
        deferred.resolve();
        $scope.env.loading = false;
      });
      return deferred.promise;
    }

    $scope.$parent.init.push(initPage);


    $scope.pay = function (form) {
      $scope.env.submit = true;
      if (form.$invalid) {
        return false;
      }
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

      if ($scope.model.id == null) {
        advFactory.store(data).then(
            function (response) {
              $scope.model = response;

              if (response.status != 'active') {
                $scope.env.action = 'payment';
                $scope.env.guid = advFactory.guid($scope.model.id);
                localStorage.setItem("advert_id", $scope.model.id);
              } else {
                window.location.href = '/';
              }

            },
            function (error) {
              // $scope.env.send = false;
              //$scope.env.submit = false;
            }
        )
      } else {
        $scope.model.update(data).then(function () {
              $scope.env.send = false;

              $scope.env.action = 'payment';

              $scope.env.submit = false;

            }, function (error) {
              $scope.env.send = false;
              $scope.env.submit = false;

              console.log(error)
            }
        )
      }
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
    $scope.newAdvert = function () {
      $scope.model = default_model;
      $scope.env.restore_flag = false;
      localStorage.setItem("advert_id", null);
      $scope.env.action = 'form'
    };

  }
})();


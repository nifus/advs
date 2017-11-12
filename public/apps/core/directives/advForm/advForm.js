(function (angular) {
  'use strict';


  function advFormDirective(advFactory, $interval, $q, $filter, Upload, advPaymentFactory, tariffFactory) {
    return {
      ngModel: 'require',
      replace: true,
      restrict: 'E',
      controller: advFormController,
      templateUrl: '/apps/core/directives/advForm/advForm.html',
      scope: {
        model: '=',
        onSave: '=',
        user: '='
      }
    };


    function advFormController($scope) {


      $scope.env = {
        payment_type: 'pay_pal',
        tariffs: [],
        tariff: null,
        display_additional_information: false,
        display_preview: false,
        categories: [],
        progress: 0,
        upload_files: false,
        submit: false,
        move_date: !$scope.model.move_date ? null : $scope.model.move_date,
        is_business_rent: 0,
        is_business_sale: 0,
        energy_source: advFactory.energy_source,
        heating: advFactory.heating,
        energy_class: advFactory.energy_class,
        address: !$scope.model.address ? {display_house: true} : angular.copy($scope.model.address),
        display_addr_details: false,
        display_addr_error: false,
        tmp_address: null,
        map: null,
        advert: null,
        restore_flag: false,
        loading: true,

        limited: false
      };
      var promises = [];

      var data_set_promise = advFactory.getDataSets().then(function (response) {
        $scope.env.categories = response.categories;
        $scope.env.subcats = response.sub_categories;
        $scope.env.equipments = response.equipments;
      });
      promises.push(data_set_promise);


      $q.all(promises).then(function () {
        $scope.env.loading = false;
        $scope.env.category_name = getCategoryName($scope.model.category);
        $scope.env.limited = $scope.model.status != 'payment_waiting' && $scope.model.status;
        if ($scope.model.id) {
          $scope.model.guid = advFactory.guid($scope.model.id);
        }

      });

      $scope.setDisplayAdditionalInformation = function (flag) {
        $scope.env.display_additional_information = flag
      }
      $scope.preview = function (form) {
        $scope.env.submit = true;
        if (!form.$invalid) {
          $scope.env.display_preview = true
        }
      }

      $scope.setCategory = function (category) {
        $scope.model.category = category;
      };

      $scope.setType = function (type) {
        $scope.model.type = type;
        //$scope.model.category = category;
        //$scope.env.step=2;
      };

      $scope.setPrivateType = function (type, category) {
        $scope.model.type = type;
        $scope.model.category = category;
        $scope.env.is_business_rent = 0;
        $scope.env.is_business_sale = 0;
      };

      $scope.setBusinessType = function (type, category) {
        $scope.model.type = type;
        $scope.model.category = category;
      };

      $scope.isBusiness = function (type) {
        $scope.model.type = null;
        $scope.model.category = null;
        if (type == 'sale') {
          $scope.env.is_business_rent = 0;
        } else {
          $scope.env.is_business_sale = 0;
        }
      };

      $scope.$watch('env.move_date', function (value) {
        $scope.model.move_date = $filter('date')(value, 'dd-MM-yyyy');
      });

      $scope.$watch('env.address', function (value) {
        if (value.value != undefined && value.details && value.details.address_components) {
          for (var i in value.details.address_components) {
            var el = angular.copy(value.details.address_components[i]);
            if (el.types.indexOf('street_number') != -1) {
              $scope.model.address.house_number = (el.long_name);
            }
            if (el.types.indexOf('route') != -1) {
              $scope.model.address.street = (el.long_name);
            }
            if (el.types.indexOf('locality') != -1) {
              $scope.model.address.city = (el.long_name);
            }
            if (el.types.indexOf('country') != -1) {
              $scope.model.address.country = (el.short_name);
            }
            if (el.types.indexOf('postal_code') != -1) {
              $scope.model.address.zip = (el.long_name);
            }
            if (el.types.indexOf('administrative_area_level_1') != -1) {
              $scope.model.address.region = (el.long_name);
            }
          }
          $scope.env.tmp_address = value.details.formatted_address;
          $scope.model.lat = value.details.geometry.location.lat();
          $scope.model.lng = value.details.geometry.location.lng();
          $scope.initGoogleMap($scope.model.lat, $scope.model.lng);
        } else if (value.display_house) {
          return;
        } else {
          $scope.model.address = {
            display_house: true
          };
          $scope.model.lat = null;
          $scope.model.lng = null;
          $scope.env.display_addr_error = true;
          $scope.env.display_addr_details = false;
        }
      }, true);

      $scope.changeEquipment = function (equipment) {
        var index = $scope.model.equipments.indexOf(equipment);
        if (index==-1){
          $scope.model.equipments.push(equipment)
        }else{
          $scope.model.equipments.splice(index,1)
        }
      };

      $scope.initGoogleMap = function (lat, lng, reload) {
        var interval = $interval(function () {
          if ($('#address_field').length == 1) {
            $interval.cancel(interval);
            $('#address_field').on('blur', function () {
              $scope.env.address.value = $scope.env.tmp_address;
              $scope.$apply();
            })
          }
        }, 4000);

        if (lat == null || lng == null) {
          return;
        }
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        if ($scope.env.map == null || reload == true) {

          var interval = $interval(function () {
            if (document.getElementById('map')) {
              $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: (lat), lng: (lng)},
                zoom: 18
              });
              $scope.env.marker = new google.maps.Marker({
                position: {lat: (lat), lng: (lng)},
                map: $scope.env.map,
                draggable: true,
                animation: google.maps.Animation.DROP
              });

              $scope.env.marker.addListener('dragend', function () {
                var c = this.getPosition();
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                  'latLng': c
                }, function (results, status) {

                  if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                      $scope.model.lat = results[0].geometry.location.lat();
                      $scope.model.lng = results[0].geometry.location.lng();
                    }

                  }
                });
              });
              $interval.cancel(interval);
            }
          }, 1000)

        } else {

          $scope.env.marker.setPosition(new google.maps.LatLng(lat, lng));
          $scope.env.map.setCenter($scope.env.marker.getPosition());
        }
      };

      $scope.uploadFiles = function (files) {
        var left = 12 - $scope.model.photos.length;
        if (left == 0) {
          return;
        } else {
          files = files.splice(0, left);
        }


        if (files && files.length) {
          $scope.env.upload_files = true;
          Upload.upload({
            url: '/api/adv/upload-images',
            data: {
              files: files
            }
          }).then(function (response) {
            // $timeout(function () {
            for (var i in response.data.images) {
              $scope.model.photos.push(response.data.images[i]);
            }
            $scope.env.upload_files = false;
            //});
          }, function (response) {
            if (response.status > 0) {
              $scope.errorMsg = response.status + ': ' + response.data;
            }
            $scope.env.upload_files = false;
          }, function (evt) {
            $scope.env.progress =
                Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
          });
        }
      };

      $scope.removePhoto = function ($index) {
        $scope.model.photos.splice($index, 1)
      };



      $scope.save = function (data, form) {
        $scope.env.submit = true;
        if (!form.$invalid) {
          $scope.env.send = true;
          $scope.onSave(data);

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

      function getCategoryName(id) {
        for (var i in $scope.env.categories) {
          if ($scope.env.categories[i].id == id) {
            return $scope.env.categories[i].title
          }
        }
      }


    }


  }

  angular.module('core').directive('advForm', ['advFactory', '$interval', '$q', '$filter', 'Upload', 'advPaymentFactory','tariffFactory', advFormDirective]);


})(window.angular);

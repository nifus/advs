(function (angular) {
    'use strict';


    function advFormDirective(advFactory, $interval, $q, tariffFactory, $filter, Upload, advPaymentFactory, $timeout, $state) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            controller: advFormController,
            templateUrl: '/apps/core/directives/advForm/advForm.html',
            scope: {
                model: '=',
                onSave: '=',
            }
        };


        function advFormController($scope) {

            $scope.env = {
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
                $scope.env.limited = $scope.model.status!='payment_waiting' && $scope.model.status;
                if ($scope.model.id){
                    $scope.env.guid = advFactory.guid($scope.model.id);
                }

            });

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

    angular.module('core').directive('advForm', ['advFactory', '$interval', '$q', 'tariffFactory', '$filter', 'Upload', 'advPaymentFactory', '$timeout', '$state', advFormDirective]);


})(window.angular);

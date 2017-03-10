(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', createAdvController);

    createAdvController.$inject = ['$scope', 'advFactory', '$filter', '$interval', '$q', 'tariffFactory'];

    function createAdvController($scope, advFactory, $filter, $interval, $q, tariffFactory) {


        $scope.env = {
            submit: false,
            move_date: null,
            is_business_rent: 0,
            is_business_sale: 0,
            energy_source: advFactory.energy_source,
            heating: advFactory.heating,
            energy_class: advFactory.energy_class,
            address: {},
            display_addr_details: false,
            display_addr_error: false,
            tmp_address: null,
            map: null,
            advert: null,
            restore_flag: false,
            tariffs: [],
            loading: true,
            tariff: null,
            action: 'form'
        };

        var promises = [];
        $scope.model = {
            type: null,
            category: null,
            subcategory: 'Any',
            photos: [],
            address: {},
            energy: {
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
            building_permission: 'Yes'
        };

        function initPage(deferred) {
            $scope.env.user = $scope.$parent.env.user;

            $scope.model.author.sex = $scope.env.user.sex;
            $scope.model.author.name = $scope.env.user.name;
            $scope.model.author.surname = $scope.env.user.surname;
            $scope.model.author.email = $scope.env.user.email;
            $scope.model.author.phone = $scope.env.user.phone;
            var data_set_promise = advFactory.getDataSets().then(function (response) {
                $scope.env.subcats = response.sub_categories;
                $scope.env.equipments = response.equipments;

            });
            promises.push(data_set_promise);
            var restore_advert_id = localStorage.getItem("advert_id");
            if (restore_advert_id == null) {
                var adv_restore_promise = advFactory.restoreAdvert(104).then(function (response) {
                    $scope.env.advert = response;
                    $scope.env.action = 'preview';
                    $scope.env.restore_flag = true;
                });
                promises.push(adv_restore_promise);

            }
            var prices_promise = tariffFactory.getPrivatePrices().then(function (response) {
                $scope.env.tariffs = response;
            });
            promises.push(prices_promise);

            //getBusinessTariffs()
            $q.all(promises).then(function () {
                deferred.resolve();
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.setPrivateTariff = function (tariff) {

            $scope.env.tariff = tariff;
            $scope.env.tariff.price = ($scope.env.advert.type == 'rent') ? tariff.rent_price : tariff.sale_price;
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

        $scope.setPrivateType = function (type, category) {
            $scope.model.type = type;
            $scope.model.category = category;
            $scope.env.is_business_rent = 0;
            $scope.env.is_business_sale = 0;

            var interval = $interval(function () {
                if ($('#address_field').length == 1) {
                    $interval.cancel(interval);
                    $('#address_field').on('blur', function () {
                        $scope.env.address.value = $scope.env.tmp_address;
                        $scope.$apply();
                    })
                }
            }, 4000)

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

        $scope.save = function (data) {
            $scope.env.submit = true;
            if (!$scope.adv_form.$invalid) {
                $scope.env.send = true;
                if ( $scope.env.advert==null ){
                    advFactory.store(data).then(
                        function (response) {
                            $scope.env.send = false;
                            $scope.env.advert = response;
                            localStorage.setItem("advert_id", $scope.env.advert.id);
                            $scope.env.action = 'preview';
                        },
                        function (error) {
                            $scope.env.send = false;
                            console.log(error)
                        }
                    )
                }else{
                    $scope.env.advert.update(data).then(function (response) {
                            $scope.env.send = false;
                            $scope.env.advert = response;
                            $scope.env.action = 'preview';
                        }, function (error) {
                            $scope.env.send = false;
                            console.log(error)
                        }
                    )
                }

            }

        };

        $scope.deleteAdvert = function () {
            alertify.confirm($filter('translate')("Are you sure  want to delete this advert?"), function (e) {
                if (e) {
                    $scope.env.advert.delete().then(function (response) {
                        window.location.reload(true)
                    })
                }
            });
        };

        $scope.backToForm = function () {
            $scope.env.action = 'form';
            $scope.model = $scope.env.advert;
        };

        $scope.$watch('model', function (value) {
            console.log(value)
        }, true);


        $scope.$watch('env.move_date', function (value) {
            $scope.model.move_date = $filter('date')(value, 'dd-MM-yyyy');
        });


        $scope.$watch('env.address', function (value) {
            var sum = 0;
            if (value.value != undefined && value.details && value.details.address_components) {
                for (var i in value.details.address_components) {
                    var el = angular.copy(value.details.address_components[i]);

                    if (el.types.indexOf('street_number') != -1) {
                        sum++;
                        $scope.model.address.house_number = (el.long_name);

                    }
                    if (el.types.indexOf('route') != -1) {
                        sum++;
                        $scope.model.address.street = (el.long_name);

                    }
                    if (el.types.indexOf('locality') != -1) {
                        sum++;
                        $scope.model.address.city = (el.long_name);
                    }

                    if (el.types.indexOf('country') != -1) {
                        sum++;
                        $scope.model.address.country = (el.short_name);

                    }
                    if (el.types.indexOf('postal_code') != -1) {
                        sum++;
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
            } else {
                $scope.model.address = {};
                $scope.model.lat = null;
                $scope.model.lng = null;
                $scope.env.display_addr_error = true;
                $scope.env.display_addr_details = false;
            }
        }, true);

        $scope.initGoogleMap = function (lat, lng, reload) {

            if (lat == null || lng == null) {
                return;
            }
            if ($scope.env.map == null || reload == true) {
                var interval = $interval(function () {
                    if (document.getElementById('map')) {
                        $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: lat, lng: lng},
                            zoom: 18
                        });
                        $scope.env.marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
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
                        // $scope.env.map.setCenter( {lat: $scope.adv.lat*1, lng: $scope.adv.lng*1} )
                        $interval.cancel(interval);
                    }
                }, 1000)

            } else {

                $scope.env.marker.setPosition(new google.maps.LatLng(lat, lng));
                $scope.env.map.setCenter($scope.env.marker.getPosition());
            }
        }

    }
})();


(function () {
    'use strict';
    angular.module('privateApp').controller('profileController', ['$scope',  '$q', '$window', '$filter','$interval',profileController]);


    function profileController($scope, $q, $window, $filter, $interval) {
        $scope.promises = null;
        $scope.env = {
            loading: true,
            specialization: null,
            service: null,
            action: 'form'
        };
        $scope.model = {
            company_data: {},
            company_data_source: 'settings',
            specializations: {
                common:[],
                personal:[]
            },
            services:{
                common:[],
                personal:[]
            }
        };
        $scope.autocomplete = {};

        $scope.$watch('autocomplete', function (value) {
            if (value.details && value.details.address_components.length == 7) {
                console.log(value)
                $scope.model.company_data.address_street = value.details.address_components[1].short_name;
                $scope.model.company_data.address_number = value.details.address_components[0].short_name;
                $scope.model.company_data.address_zip = value.details.address_components[6].short_name;
                $scope.model.company_data.address_city = value.details.vicinity;
                $scope.model.company_data.lat = value.details.geometry.location.lat();
                $scope.model.company_data.lng = value.details.geometry.location.lng();
            }
        }, true);

        function initPage(deferred) {
            $window.document.title = $filter('translate')('My profile');


            var profilePromise = null;
            if ( $scope.$parent.user.profile==1 ){
                profilePromise = $scope.$parent.user.getProfile().then(function (response) {
                    if ( response.id ){
                        $scope.model = response;
                    }
                })
            }
            $q.all([profilePromise]).then(function () {
                $scope.env.loading = false;

            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.activateProfile = function () {
            $scope.$parent.user.activateProfile().then(function (response) {
                alertify.success($filter('translate')("Profile activated"));
            });
            $scope.$parent.user.getProfile().then(function (response) {
                if ( response.id ){
                    $scope.model = response;
                }
            })
        };
        $scope.deactivateProfile = function () {
            $scope.$parent.user.deactivateProfile().then(function (response) {
                alertify.success($filter('translate')("Profile deactivated"));
            })
        };
        $scope.addSpecialization = function () {
            if ($scope.env.specialization==null){
                return;
            }
            $scope.model.specializations.personal.push($scope.env.specialization);
            $scope.env.specialization = null;
        };
        $scope.addService = function () {
            if ($scope.env.service==null){
                return;
            }
            $scope.model.services.personal.push($scope.env.service);
            $scope.env.service = null;
        };
        $scope.save = function () {
            $scope.$parent.user.updateProfile($scope.model).then(function (response) {
                alertify.success($filter('translate')("Profile updated"));

            })
        };
        $scope.preview = function () {
            $scope.env.action = 'preview'
            initGoogleMap($scope.model.company_data.lat, $scope.model.company_data.lng);
        };
        $scope.close = function () {
            $scope.env.action = 'form';

        }

        function initGoogleMap (lat, lng, reload) {


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
                            draggable: false,
                            animation: google.maps.Animation.DROP
                        });


                        $interval.cancel(interval);
                    }
                }, 1000)

            } else {

                $scope.env.marker.setPosition(new google.maps.LatLng(lat, lng));
                $scope.env.map.setCenter($scope.env.marker.getPosition());
            }
        };


    }
})();


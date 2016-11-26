(function (angular) {
    'use strict';

    function citySelectDirective($q, $http, $timeout) {
        return {
            restrict: 'E',
            link: citySelectLink,
            controller: citySelectController,
            templateUrl: '/apps/frontApp/directives/citySelect/citySelect.html',
            scope: {
                ngModel: '='
            }
        };


        function citySelectLink($scope, element) {

            element.find('input').on('keyup', function (e) {
                $scope.getCities($(this).val())
            });

            element.find('input').on('keydown', function (e) {
                if (e.keyCode == 40) {
                    //  down
                    if ($scope.env.selected < $scope.env.search_results.length - 1) {
                        $scope.env.selected = $scope.env.selected + 1;
                    }
                    e.preventDefault();

                } else if (e.keyCode == 38) {
                    //  up
                    if ($scope.env.selected > 0) {
                        $scope.env.selected = $scope.env.selected - 1;
                    }
                    e.preventDefault();
                }
                else if (e.keyCode == 13) {
                    $scope.select($scope.env.search_results[$scope.env.selected])
                }
            });
            $timeout(function () {
                element.find('input').focus();
            }, 1000);
        }


        function citySelectController($scope) {

            $scope.env = {
                city: null,
                zoom_radius: [
                    {radius: "1", zoom: 12},
                    {radius: "5", zoom: 11},
                    {radius: "10", zoom: 10},
                    {radius: "15", zoom: 10},
                    {radius: "20", zoom: 9},
                    {radius: "30", zoom: 9},
                    {radius: "50", zoom: 8},
                    {radius: "80", zoom: 8}
                ],
                search_results: [],
                display_map: false,
                selected: -1,
                old_search_key: null,
                map: null,
                circle: null,
                location: null
            };

            $scope.getCities = function (value) {
                if (value == $scope.env.old_search_key) {
                    return;
                }
                if (value.length <= 1) {
                    $scope.env.search_results = [];
                }
                $scope.env.old_search_key = value;
                $http.get('/api/search/cities/' + value).then(function (response) {
                    var result = [];
                    if (response.data.cities.length > 0) {
                        for (var i in response.data.cities) {
                            response.data.cities[i].description = response.data.cities[i].city;
                        }
                        $scope.env.search_results = response.data.cities
                    } else {
                        var autocompleteService = new google.maps.places.AutocompleteService();
                        autocompleteService.getQueryPredictions({input: value}, function (predictions, status) {
                            predictions.forEach(function (prediction) {
                                if (prediction.types && (prediction.types.indexOf('locality') == -1 && prediction.types.indexOf('postal_code') == -1)) {
                                    return;
                                }
                                result.push(prediction)
                            });
                            $scope.env.search_results = result
                        });
                    }
                });
            };

            $scope.select = function (row) {

                if (row.lat && row.lng) {
                    $scope.env.city = row.description;

                    initMap(row.lat, row.lng, $scope.env.radius).then(function (response) {
                        $scope.env.map = response.map;
                        $scope.env.circle = response.circle;
                    });
                    $scope.ngModel.lat = row.lat;
                    $scope.ngModel.lng = row.lng;
                    $scope.ngModel.radius = $scope.env.radius;
                    $scope.ngModel.address = $scope.env.city;
                } else {
                    var service = new google.maps.places.PlacesService(document.getElementById('map'));
                    service.getDetails(row, function (response) {
                        $scope.env.city = response.formatted_address;
                        var location = response.geometry.location;
                        initMap(location.lat(), location.lng(), $scope.env.radius).then(function (response) {
                            $scope.env.map = response.map;
                            $scope.env.circle = response.circle;
                        });
                        $scope.ngModel.lat = location.lat();
                        $scope.ngModel.lng = location.lng();
                        $scope.ngModel.radius = $scope.env.radius;
                        $scope.ngModel.address = response.formatted_address;
                    });

                }
                $scope.env.search_results = [];

            };

            $scope.$watch('env.radius', function (value) {

                if ($scope.env.map == null) {
                    return;
                }
                if ($scope.env.circle != null) {
                    $scope.env.map.setZoom(getZoom(value));
                    $scope.env.circle.setRadius(value * 1000)
                }
                $scope.ngModel.radius = value;
            });

            $scope.$watch('ngModel', function (value) {
                if (angular.isObject(value)) {
                    if (value.radius != undefined) {
                        $scope.env.radius = value.radius.toString();
                    }
                    if (value.lat != undefined) {
                        $scope.env.lat = value.lat;
                    }
                    if (value.lng != undefined) {
                        $scope.env.lng = value.lng;
                    }
                    if (value.address != undefined) {
                        initMap($scope.env.lat, $scope.env.lng, $scope.env.radius).then(function (response) {
                            $scope.env.map = response.map;
                            $scope.env.circle = response.circle;
                        });
                        $scope.env.city = value.address;
                    }
                }

            });

            function initMap(lat, lng, radius) {
                var defer = $q.defer();
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: lat, lng: lng},
                    zoom: getZoom(radius)
                });
                google.maps.event.addListenerOnce(map, 'idle', function () {
                    var circle = new google.maps.Circle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        map: map,
                        center: {lat: lat, lng: lng},
                        radius: radius * 1000
                    });
                    defer.resolve({map: map, circle: circle});
                });
                return defer.promise;
            }


            function getZoom(radius) {
                for (var i in $scope.env.zoom_radius) {
                    if ($scope.env.zoom_radius[i].radius == radius) {
                        return $scope.env.zoom_radius[i].zoom;
                    }
                }
            }
        }
    }

    angular.module('frontApp').directive('citySelect', citySelectDirective);


})(window.angular);

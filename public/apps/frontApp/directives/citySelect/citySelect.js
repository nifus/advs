(function (angular) {
    'use strict';

    function citySelectDirective($compile, $http, $q, $timeout) {
        return {
            restrict: 'E',
            link: citySelectLink,
            scope: {
                ngModel: '=',
                radius: '=',
                cities:'='
            }
        };

        function citySelectLink($scope, element) {
            var zoom_radius = {
                1 : 13,
                5 : 11,
                10 : 10,
                15 : 9,
                20 : 8,
                25 : 7,
            };
            var html = '<ul class="autocomplete" ng-show="rows.length>0">';
            html += '<li ng-repeat="row in rows" ng-click="select(row)">{{row.city}}</li>';
            html += '</ul>';
            var linkFn = $compile(html);

            var content = linkFn($scope);
            element.append(content);

            $scope.select = function (row) {

                if (row.place_id) {
                    $http.get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' + row.place_id + '&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU').then(function (response) {
                        $scope.rows = [];
                        element.find('input').val(row.city)
                        $scope.ngModel = row;
                        var location = response.data.result.geometry.location;
                       // var zoom = getZoom(response.data.result.geometry.viewport.northeast, response.data.result.geometry.viewport.southwest) + 1;
                        var zoom = zoom_radius[ $scope.radius ];

                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: location.lat, lng: location.lng},
                            zoom: zoom
                        });

                        var cityCircle = null;

                        google.maps.event.addListenerOnce(map, 'idle', function () {
                            cityCircle = new google.maps.Circle({
                                strokeColor: '#FF0000',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#FF0000',
                                fillOpacity: 0.35,
                                map: map,
                                center: {lat: location.lat, lng: location.lng},
                                radius: $scope.radius*1000
                            });
                        });

                       /* map.addListener('idle', function () {
                            var radius = getRadius(map);
                            console.log(radius)
                        });*/

                        $scope.$watch('radius', function(value){
                            if ( cityCircle!=null){
                                map.setZoom( zoom_radius[value]);
                                cityCircle.setRadius( value*1000 )
                            }
                            $http.get('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' + location.lat+','+location.lng + '&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU&types=locality&radius='+value*1000).then(function (response) {
                                $scope.cities = response.data.results;
                                console.log(response.data)
                            })
                        });




                        /*
                         google.maps.event.addListenerOnce(map, 'idle', function(){
                         var radius = getRadius(map);
                         var cityCircle = new google.maps.Circle({
                         strokeColor: '#FF0000',
                         strokeOpacity: 0.8,
                         strokeWeight: 2,
                         fillColor: '#FF0000',
                         fillOpacity: 0.35,
                         map: map,
                         center: {lat: location.lat, lng: location.lng},
                         radius: radius
                         });
                         //console.log( getR(map) );
                         map.setZoom(zoom - 2);
                         console.log(radius)
                         $http.get('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' + location.lat+','+location.lng + '&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU&types=locality&radius='+radius).then(function (response) {
                         console.log(response)
                         })
                         });
                         google.maps.event.addListenerOnce(map, 'idle', function(){
                         var radius = getRadius(map);
                         var cityCircle = new google.maps.Circle({
                         strokeColor: '#FF0000',
                         strokeOpacity: 0.8,
                         strokeWeight: 2,
                         fillColor: '#FF0000',
                         fillOpacity: 0.35,
                         map: map,
                         center: {lat: location.lat, lng: location.lng},
                         radius: radius
                         });
                         //console.log( getR(map) );
                         map.setZoom(zoom - 2);
                         console.log(radius)
                         $http.get('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' + location.lat+','+location.lng + '&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU&types=locality&radius='+radius).then(function (response) {
                         console.log(response)
                         })
                         });*/


                    })
                }


            };


            element.find('input').on('keyup', function (e) {
                var value = $(this).val();
                if (value.length > 1) {
                    loadData(value).then(function (cities) {
                        $scope.rows = cities;
                    })
                }
                //

            })


        }

        function getZoom(ne, se) {
            var width = $("#map").width();
            var height = $("#map").height();
            var dlat = Math.abs(ne.lat - se.lat);
            var dlon = Math.abs(ne.lng - se.lng);
            var max = 0;
            if (dlat > dlon) {
                max = dlat;
            } else {
                max = dlon;
            }
            var clat = Math.PI * Math.abs(se.lat + ne.lat) / 360.;
            var C = 0.0000107288;
            var z0 = Math.ceil(Math.log(dlat / (C * height)) / Math.LN2);
            var z1 = Math.ceil(Math.log(dlon / (C * width * Math.cos(clat))) / Math.LN2);
            //18 – это максимальный zoom для google.maps
            return 18 - ((z1 > z0) ? z1 : z0);
        }


        function getRadius(map) {
            var bounds = map.getBounds();
            var center = map.getCenter();

            if (bounds && center) {
                var ne = bounds.getNorthEast();
                // Calculate radius (in meters).
                return google.maps.geometry.spherical.computeDistanceBetween(center, ne);
            }
        }


        function loadData(value) {
            var defer = $q.defer();
            $http.get('/api/search/cities/' + value).then(function (response) {
                if (response.data.cities.length > 0) {
                    defer.resolve(response.data.cities)
                } else {
                    var result = [];

                    $http.get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' + value + '&types=(regions)&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU').then(function (response) {

                        for (var i in response.data.predictions) {
                            var obj = response.data.predictions[i];
                            if (obj.types.indexOf('locality') == -1) {
                                continue;
                            }
                            result.push(
                                {
                                    city: obj.description,
                                    place_id: obj.place_id
                                }
                            )
                        }
                        defer.resolve(result)

                    })


                }
            })


            return defer.promise;

        }


    }

    angular.module('frontApp').directive('citySelect', citySelectDirective);


})(window.angular);

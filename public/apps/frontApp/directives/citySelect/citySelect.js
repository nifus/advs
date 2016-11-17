(function (angular) {
    'use strict';

    function citySelectDirective($q) {
        return {
            restrict: 'E',
            link: citySelectLink,
            templateUrl: '/apps/frontApp/directives/citySelect/citySelect.html',

            scope: {
                ngModel: '='
            }
        };


        function citySelectLink($scope, element) {

            var key = 'AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU';
            $scope.search = {
                radius: "1",
                city: null,
                cities: []
            };

            $scope.env = {
                zoom_radius: [
                    {radius: "1", zoom: 13},
                    {radius: "5", zoom: 12},
                    {radius: "10", zoom: 11},
                    {radius: "15", zoom: 10},
                    {radius: "20", zoom: 9},
                    {radius: "30", zoom: 8},
                    {radius: "50", zoom: 7},
                    {radius: "80", zoom: 6}
                ],
                search_results: [],
                display_map: false
            };

            var map = null;
            var cityCircle = null;
            var location = null;


            $scope.select = function (row) {
                var service = new google.maps.places.PlacesService(document.getElementById('map'));
                service.getDetails(row, function (response) {
                    element.find('input').val(response.formatted_address);
                    location = response.geometry.location;
                    console.log( {lat: location.lat(), lng: location.lng()})

                    map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: location.lat(), lng: location.lng()},
                            zoom: getZoom($scope.search.radius)
                        });
                        google.maps.event.addListenerOnce(map, 'idle', function () {
                            cityCircle = new google.maps.Circle({
                                strokeColor: '#FF0000',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#FF0000',
                                fillOpacity: 0.35,
                                map: map,
                                center: {lat: location.lat(), lng: location.lng()},
                                radius: $scope.search.radius * 1000
                            });
                        });
                        $scope.search.cities=[];

                    updatePlaces()
                });
                $scope.env.search_results = [];
            };

            function updatePlaces() {


                var service = new google.maps.places.PlacesService(map);
                var request  = {
                    key: key,
                    location: location,
                    radius:  $scope.search.radius*1000,
                     types: ['locality','sublocality']
                }
                $scope.search.cities = [];
                service.nearbySearch(request, function(response,status, pagination){

                    console.log(response)
                    response.forEach( function(city){
                        //if (city.types.indexOf('political')!==-1 && city.types.indexOf('locality')!==-1){
                            $scope.search.cities.push(city.name);
                        //}
                    });

                   // $scope.search.cities = cities;
                    if (pagination.hasNextPage) {
                        pagination.nextPage();
                    }else{
                        $scope.$apply();
                    }

                });
            }

            $scope.cityAutocomplete = function (value) {
                var defer = $q.defer();
                var autocompleteService = new google.maps.places.AutocompleteService();
                var result = [];
                autocompleteService.getQueryPredictions({input: value, key: key}, function (predictions, status) {
                    predictions.forEach(function (prediction) {
                        if (prediction.types && prediction.types.indexOf('locality') == -1) {
                            return;
                        }
                        result.push(prediction)
                    });
                    defer.resolve(result)
                });
                return defer.promise;
            };

            $scope.$watch('search.radius', function (value) {
                if ( map==null ){
                    return;
                }
                if (cityCircle != null) {
                    map.setZoom( getZoom(value) );
                    cityCircle.setRadius(value * 1000)
                }

                updatePlaces()
            });

            $scope.removeCity = function(index){
                $scope.search.cities = $scope.search.cities.splice(index,1);
            };

            element.find('input').on('keyup', function (e) {
                var value = $(this).val();
                if (value.length > 1) {
                    $scope.cityAutocomplete(value).then(function (response) {
                        $scope.env.search_results = response;
                    })
                }
            })

            function getZoom(radius) {
                for (var i in $scope.env.zoom_radius) {
                    if ($scope.env.zoom_radius[i].radius == radius) {
                        return $scope.env.zoom_radius[i].zoom;
                    }
                }
            }

            $scope.$watch('search', function(value){
                $scope.ngModel=value;
            }, true)


        }


    }

    angular.module('frontApp').directive('citySelect', citySelectDirective);


})(window.angular);

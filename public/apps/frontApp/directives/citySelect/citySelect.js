(function (angular) {
    'use strict';

    function citySelectDirective($q, $http, $timeout) {
        return {
            restrict: 'E',
            link: citySelectLink,
            templateUrl: '/apps/frontApp/directives/citySelect/citySelect.html',

            scope: {
                ngModel: '='
            }
        };


        function citySelectLink($scope, element) {

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
                display_map: false,
                selected: -1,
                old_search_key: null
            };

            var map = null;
            var cityCircle = null;
            var location = null;

            element.find('input').on('keyup', function (e) {

                var value = $(this).val();
                if (value==$scope.env.old_search_key){
                    return;
                }
                if (value.length > 1) {
                    $scope.cityAutocomplete(value).then(function (response) {
                        $scope.env.search_results = response;
                    })
                }
            });
            element.find('input').on('keydown', function (e) {
                if ( e.keyCode==40 ){
                        //  вниз
                    if ( $scope.env.selected<$scope.env.search_results.length-1){
                        $scope.env.selected=$scope.env.selected+1;
                    }
                    e.preventDefault();
                    $scope.$apply()
                }else if( e.keyCode==38 ){
                    //  вверх
                    if ( $scope.env.selected>0){
                        $scope.env.selected=$scope.env.selected-1;
                    }
                    e.preventDefault();
                    $scope.$apply()
                }
                else if( e.keyCode==13 ){
                    $scope.select($scope.env.search_results[$scope.env.selected])
                }
            });
            $timeout(function(){
                element.find('input').focus();
            },1000);


            $scope.cityAutocomplete = function (value) {
                $scope.env.old_search_key = value;
                var defer = $q.defer();

                $http.get('/api/search/cities/'+value).then(function (response) {
                    var result = [];

                    if (response.data.cities.length>0){
                        for( var i in response.data.cities){
                            response.data.cities[i].description = response.data.cities[i].city;
                        }
                        defer.resolve(response.data.cities);
                    }else{
                        var autocompleteService = new google.maps.places.AutocompleteService();
                        autocompleteService.getQueryPredictions({input: value}, function (predictions, status) {
                            predictions.forEach(function (prediction) {
                                if (prediction.types && prediction.types.indexOf('locality') == -1) {
                                    return;
                                }
                                result.push(prediction)
                            });
                            defer.resolve(result)
                        });
                    }
                });

                return defer.promise;
            };

            $scope.select = function (row) {
                if (row.lat && row.lng ){
                    $scope.ngModel={lat: row.lat, lng: row.lng, radius: $scope.search.radius * 1000, city_id: row.id};
                    location = {lat: row.lat, lng: row.lng};
                        element.find('input').val(row.description);
                        map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: row.lat, lng: row.lng},
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
                                center: {lat: row.lat, lng: row.lng},
                                radius: $scope.search.radius * 1000
                            });
                        });
                        $scope.search.cities=[];
                        //updatePlaces()

                }else{
                    var service = new google.maps.places.PlacesService(document.getElementById('map'));
                    service.getDetails(row, function (response) {
                        element.find('input').val(response.formatted_address);
                        location = response.geometry.location;
                        $scope.ngModel={lat: location.lat, lng: location.lng, radius: $scope.search.radius * 1000};

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

                        //updatePlaces()
                    });
                }

                $scope.env.search_results = [];
                $scope.$apply();
            };

            function updatePlaces() {
                var service = new google.maps.places.PlacesService(map);
                var request  = {
                   // key: key,
                    location: location,
                    radius:  $scope.search.radius*1000,
                     types: ['locality','sublocality']
                };
                $scope.search.cities = [];
                service.nearbySearch(request, function(response,status, pagination){

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



            $scope.$watch('search.radius', function (value) {
                if ( map==null ){
                    return;
                }
                if (cityCircle != null) {
                    map.setZoom( getZoom(value) );
                    cityCircle.setRadius(value * 1000)
                }
                $scope.ngModel={lat: location.lat, lng: location.lng, radius: $scope.search.radius * 1000};
                //updatePlaces()
            });

            function getZoom(radius) {
                for (var i in $scope.env.zoom_radius) {
                    if ($scope.env.zoom_radius[i].radius == radius) {
                        return $scope.env.zoom_radius[i].zoom;
                    }
                }
            }

            $scope.$watch('search', function(value){
              //  $scope.ngModel=value;
            }, true)


        }


    }

    angular.module('frontApp').directive('citySelect', citySelectDirective);


})(window.angular);

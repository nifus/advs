(function (angular) {
    'use strict';

    function citySelectDirective($q, $http, $timeout) {
        return {
            restrict: 'E',
            link: citySelectLink,
            templateUrl: '/apps/frontApp/directives/citySelect/citySelect.html',

            scope: {
                ngModel: '=',
                radius: '='

            }
        };


        function citySelectLink($scope, element) {


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
                old_search_key: null,
                radius: "1"
            };
            $scope.env.radius = $scope.radius==undefined ? "1" : $scope.radius;


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
                    $scope.ngModel={lat: row.lat, lng: row.lng, radius: $scope.env.radius , address: row.description};
                    location = {lat: row.lat, lng: row.lng};
                        element.find('input').val(row.description);
                        map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: row.lat, lng: row.lng},
                            zoom: getZoom($scope.env.radius)
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
                                radius: $scope.env.radius * 1000
                            });
                        });



                }else{
                    var service = new google.maps.places.PlacesService(document.getElementById('map'));
                    service.getDetails(row, function (response) {
                        element.find('input').val(response.formatted_address);
                        location = response.geometry.location;
                        $scope.ngModel={lat: location.lat(), lng: location.lng(), radius: $scope.env.radius ,address: response.formatted_address };
                        $scope.$apply();

                        map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: location.lat(), lng: location.lng()},
                            zoom: getZoom($scope.env.radius)
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
                                radius: $scope.radius * 1000
                            });
                        });

                        //updatePlaces()
                    });
                }

                $scope.env.search_results = [];
                //$scope.$apply();
            };


            $scope.$watch('env.radius', function (value) {
                if ( map==null ){
                    return;
                }
                if (cityCircle != null) {
                    map.setZoom( getZoom(value) );
                    cityCircle.setRadius(value * 1000)
                }
                $scope.ngModel.radius = value;
            });

            $scope.$watch('radius', function (value) {
                if (value!=undefined){

                    $scope.env.radius = value+"";
                }
            });

            function getZoom(radius) {
                for (var i in $scope.env.zoom_radius) {
                    if ($scope.env.zoom_radius[i].radius == radius) {
                        return $scope.env.zoom_radius[i].zoom;
                    }
                }
            }

            $scope.$watch('ngModel', function(value){
                if ( value.address!=undefined){
                    element.find('input').val(value.address);
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: value.lat, lng: value.lng},
                        zoom: getZoom($scope.env.radius)
                    });
                    google.maps.event.addListenerOnce(map, 'idle', function () {
                        cityCircle = new google.maps.Circle({
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35,
                            map: map,
                            center: {lat: value.lat, lng: value.lng},
                            radius: $scope.radius * 1000
                        });
                    });
                }
            });



        }


    }

    angular.module('frontApp').directive('citySelect', citySelectDirective);


})(window.angular);

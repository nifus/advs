(function () {
    'use strict';
    angular.module('frontApp').controller('searchAdvController', searchAdvController);

    searchAdvController.$inject = ['$scope', 'advFactory','$http','$q','$filter'];

    function searchAdvController($scope, advFactory, $http, $q, $filter) {
        $scope.env = {
            loading: true,
            submit: false,
            address: {},
            id: null,
            promises: [],
                heating_systems:[
                $filter('translate')('Any'),
                $filter('translate')('Self-contained heating'),
                $filter('translate')('Central heating'),
                $filter('translate')('Teleheating'),
            ],
            display_city_field: true
        };
        if ( window.location.search.indexOf('id=')!==-1 ){
            $scope.env.id = window.location.search.match(/id=([0-9]*)/)[1];
        }

        var dataSetPromise = advFactory.getDataSets().then(function(response){
            $scope.env.subcats = response.sub_categories;
            $scope.env.equipments = response.equipments;
        });
        $scope.env.promises.push(dataSetPromise);

        if ($scope.env.id!=null){
            $scope.env.display_city_field=false;
            var searchPromise = $http.get('/api/search/'+$scope.env.id).then(function(response){
                if ( response.data.success==true ){
                    $scope.search = response.data.search.query;
                    $scope.env.address = {
                        address: response.data.search.query.address,
                        lat: response.data.search.query.lat,
                        lng: response.data.search.query.lng,
                        radius: response.data.search.query.radius,
                    };
                    $scope.env.place = response.data.place;
                }
            });
            $scope.env.promises.push(searchPromise);
        }
        $q.all($scope.env.promises).then( function () {
            $scope.env.loading = false;
        });

        $scope.search = {
            category: 1,
            type: ( window.location.href.indexOf('rent')===-1 ? 'sale' : 'rent'),
            subcategory:[],
            parking_place: 'Any',
            heating: 'Any',
            pets: 'Any',
            radius: "1",
            air_conditioner:"Any",
            edp_cabling:"Any",
            development:"Any",
            building_permission:"Any"
        };


        $scope.searchAdvs = function(data){
            $scope.env.submit = true;
            $http.post('/api/search', {query:data}).then(function(response){
                $scope.env.submit = false;
                if ( response.data.success==true){
                    window.location.href = '/search/'+response.data.id;
                }
            })
        }


        $scope.$watch('search', function (value) {
            console.log(value)
        }, true);



       /* $scope.$watch('env.address', function (value) {
            if ( angular.isObject(value)){
                $scope.search.lat=value.lat;
                $scope.search.lng=value.lng;
                $scope.search.radius=value.radius;
                if (value.city_id!=undefined){
                    $scope.search.city_id=value.city_id
                }
                if (value.address!=undefined){
                    $scope.search.address=value.address
                }
            }
        }, true);*/


        $scope.clearCityField = function(){
            $scope.search.city_id = undefined;
            $scope.env.display_city_field=true;
            $scope.env.address = null;
        }
    }
})();


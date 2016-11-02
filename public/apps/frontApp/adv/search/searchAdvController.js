(function () {
    'use strict';
    angular.module('frontApp').controller('searchAdvController', searchAdvController);

    searchAdvController.$inject = ['$scope', 'advFactory','$http','$q'];

    function searchAdvController($scope, advFactory, $http, $q) {

        console.log(window.location)

        $scope.env = {
            loading: true,
            submit: false,
            address: null,
            id: null,
            promises: []
        };
        if ( window.location.search.indexOf('id=')!==-1 ){
            $scope.env.id = window.location.search.match(/id=([0-9*])/)[1];
        }



        var dataSetPromise = advFactory.getDataSets().then(function(response){
            $scope.env.subcats = response.sub_categories;
            $scope.env.equipments = response.equipments;
        });
        $scope.env.promises.push(dataSetPromise);

        if ($scope.env.id!=null){
            var searchPromise = $http.get('/api/search/'+$scope.env.id).then(function(response){
                if ( response.data.success==true ){
                    $scope.search = response.data.search.query;
                }
            });
            $scope.env.promises.push(searchPromise);
        }
        $q.all($scope.env.promises).then( function () {
            $scope.env.loading = false;
        });

        $scope.search = {
            category: 1,
            type: ( window.location.href.indexOf('rent')===-1 ? 'sale' : 'rent')
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



        /*$scope.$watch('env.address', function (value) {
            if (value.details && value.details.address_components) {
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
                        $scope.model.address.country = (el.long_name);

                    }
                    if (el.types.indexOf('postal_code') != -1) {
                        $scope.model.address.zip = (el.long_name);
                    }
                }
                $scope.env.display_addr_details = true;

            }
        }, true);*/
    }
})();


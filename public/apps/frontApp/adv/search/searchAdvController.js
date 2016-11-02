(function () {
    'use strict';
    angular.module('frontApp').controller('searchAdvController', searchAdvController);

    searchAdvController.$inject = ['$scope', 'advFactory'];

    function searchAdvController($scope, advFactory) {


        $scope.env = {
            submit: false,
            address: null
        };

        advFactory.getDataSets().then(function(response){
            $scope.env.subcats = response.sub_categories;
            $scope.env.equipments = response.equipments;
        })


        $scope.search = {
            category: 1
        };





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


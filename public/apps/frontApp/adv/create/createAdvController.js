(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', createAdvController);

    createAdvController.$inject = ['$scope','userFactory','$cookies'];

    function createAdvController($scope, userFactory,  $cookies) {


        $scope.env = {
            is_business_rent : 0,
            is_business_sell : 0,
        };


        $scope.model = {
            type: null,
            category: null
        };

        $scope.types = {
            flat: [
                'Souterrain','Loft','Top floor flat','Downstairs flat','Maisonette','Penthouse','Raised ground floor','Terrace flat','Any'
            ],
            house:[
                'Single-family house','Mid-terrace town house','End-terrace town house','Multi-family house' ,'Bungalow','Farmhouse','Semi-detached house','Villa','Castle/Palace','Any'
            ],
            garage: ['Outside-parking space','Carport','Garage','Underground carpark', 'Car park', 'Any'],
            office: ['Loft/Studio','Office','Praxis','Any'],
            gastronomy:['Bar/Bistro/Cafe','Club/Disco','Restaurant','Hotel','Pension','Any'],
            hall: ['Hall (+open space)','Warehouse ( +open space)','Industrial building', 'Cold+storage ( +warehouse)', 'Car workshop' ,'Any'],
            retail:['Shopping Center']
        }

        $scope.setPrivateType = function(type, category){
            $scope.model.type = type;
            $scope.model.category = category;
            $scope.env.is_business_rent =  0;
            $scope.env.is_business_sell =  0;
        };

        $scope.setBusinessType = function(type, category){
            $scope.model.type = type;
            $scope.model.category = category;
            //$scope.env.is_business_rent =  0;
            //$scope.env.is_business_sell =  0;
        };

        $scope.isBusiness = function(type){
            $scope.model.type = null;
            $scope.model.category = null;
            if ( type=='sell' ){
                $scope.env.is_business_rent =  0;
            }else{
                $scope.env.is_business_sell =  0;
            }
        };



    }
})();


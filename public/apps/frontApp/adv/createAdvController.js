(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', createAdvController);

    createAdvController.$inject = ['$scope','userFactory','$cookies'];

    function createAdvController($scope, userFactory,  $cookies) {

        $scope.model = {
            rent: {},
            sell: {},
        };

        $scope.types = {
            flat: [
                'Souterrain','Loft','Top floor flat','Downstairs flat','Maisonette','Penthouse','Raised ground floor','Terrace flat','Any'
            ],
            house:[
                'Single-family house','Mid-terrace town house','End-terrace town house','Multi-family house' ,'Bungalow','Farmhouse'
            ]
        }

        $scope.setType = function(type){
            $scope.model.type = type;
            $scope.model[type=='sell' ? 'rent' : 'sell'] = {}
        }


    }
})();


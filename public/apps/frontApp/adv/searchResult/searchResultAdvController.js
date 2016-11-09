(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultAdvController', searchResultAdvController);

    searchResultAdvController.$inject = ['$scope', 'advFactory','$http','$q'];

    function searchResultAdvController($scope, advFactory, $http, $q) {


        $scope.env = {
            advs_on_page: 5,
            id:  window.location.href.match(/search\/([0-9]*)/)[1],
        };

        advFactory.getResult($scope.env.id, {}).then( function(response){
            $scope.env.rows= response
        })
    }
})();


(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultAdvController', searchResultAdvController);

    searchResultAdvController.$inject = ['$scope', 'advFactory','$http','$q'];

    function searchResultAdvController($scope, advFactory, $http, $q) {


        $scope.env = {
            advs_on_page: 5,
            id:  window.location.href.match(/search\/([0-9]*)/)[1],
            city: null,
            search: null,
        };

        advFactory.getResult($scope.env.id, {}).then( function(response){
            $scope.env.rows= response.advs;
            $scope.env.search= response.search;
            $scope.env.city= response.city;
        })
    }
})();


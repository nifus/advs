(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultController', searchResultController);

    searchResultController.$inject = ['$scope', 'advFactory','$http','$q'];

    function searchResultController($scope, advFactory, $http, $q) {


        $scope.env = {
            advs_on_page: 5,
            city: null,
            search: null,
            adv_tmpl: null
        };
        var params = window.location.href.match(/search\/([0-9]*)(#\/([0-9]*))?/);
        $scope.env.result_id = params[1];
        $scope.env.adv_id = params[3]!=undefined ?  params[3] : null;
        if ($scope.env.adv_id){
            $scope.env.adv_tmpl = '/api/adv/'+$scope.env.adv_id
        }


            console.log($scope.env)
        advFactory.getResult($scope.env.result_id, {}).then( function(response){
            $scope.env.rows= response.advs;
            $scope.env.search= response.search;
            $scope.env.city= response.city;
        })
    }
})();


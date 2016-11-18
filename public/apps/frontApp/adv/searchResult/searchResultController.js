(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultController', searchResultController);

    searchResultController.$inject = ['$scope', 'searchLogFactory', '$q', '$interval', '$filter'];

    function searchResultController($scope, searchLogFactory) {

        $scope.root = {
            adv_id: null,
            result_id: null,
            search: null
        };
        $scope.promises = [];

        var url = window.location.href.match(/view([0-9]*)/);
        $scope.root.adv_id = url != null && url[1] != undefined ? url[1] * 1 : null;

        url = window.location.href.match(/\/search\/([0-9]*)/);
        $scope.root.result_id = url != null && url[1] != undefined ? url[1] * 1 : null;


        var searchPromise = searchLogFactory.getById($scope.root.result_id).then(function (response) {
            $scope.root.search = response;
        });
        $scope.promises.push(searchPromise);


        $scope.openAdv = function(adv){
            console.log(adv)
        }
    }
})();


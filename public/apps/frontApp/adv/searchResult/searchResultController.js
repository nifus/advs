(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultController', searchResultController);

    searchResultController.$inject = ['$scope', 'advFactory','searchLogFactory','$q'];

    function searchResultController($scope, advFactory, searchLogFactory, $q) {


        $scope.env = {
            loading: true,
            adv_id: null,
            result_id: null,
            per_page: 5,
            city: null,
            search: null,
            adv_tmpl: null,
            sortby: null,
            page: 1
        };
        var promises = [];

        var url = window.location.href.match(/view([0-9]*)/);
        $scope.env.adv_id = url!=null && url[1]!=undefined ?  url[1]*1 : null;

        url = window.location.href.match(/\/page([0-9]*)/);
        $scope.env.page = url!=null && url[1]!=undefined ?  url[1]*1 : null;

        url = window.location.href.match(/\/search\/([0-9]*)/);
        $scope.env.result_id = url!=null && url[1]!=undefined ?  url[1]*1 : null;


        if ($scope.env.adv_id){
            $scope.env.adv_tmpl = '/api/adv/'+$scope.env.adv_id
        }


        var searchPromise = searchLogFactory.getById($scope.env.result_id).then(function (response) {
            $scope.env.search= response;
            if ($scope.env.search.config && $scope.env.search.config.per_page){
                $scope.env.per_page = $scope.env.search.config.per_page;
            }
            if ($scope.env.search.config && $scope.env.search.config.sortby){
                $scope.env.sortby = $scope.env.search.config.sortby;
            }
        });
        promises.push(searchPromise);

        var advPromise = advFactory.getResult($scope.env.result_id, {}).then( function(response){
            $scope.env.rows= response.advs;
            $scope.env.city= response.city;
            console.log(response.advs)
        });
        promises.push(advPromise);

        $q.all(promises).then(function () {
            $scope.env.loading = false;
        });

        $scope.changePerPage = function (value) {
            $scope.env.per_page = value*1;
            $scope.env.search.update(
                {
                    per_page: $scope.env.per_page,
                    sortby: $scope.env.sortby
                }
            )
        }
    }
})();


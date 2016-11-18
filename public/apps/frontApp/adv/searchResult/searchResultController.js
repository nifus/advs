(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultController', searchResultController);

    searchResultController.$inject = ['$scope', 'advFactory','searchLogFactory','$q','$interval'];

    function searchResultController($scope, advFactory, searchLogFactory, $q, $interval) {


        $scope.env = {
            loading: true,
            adv_id: null,
            result_id: null,
            per_page: 5,
            city: null,
            search: null,
            adv_tmpl: null,
            sortby: null,
            page: 1,
            pages: null,
            current:[],
            display_map: false,
            map: null
        };
        var promises = [];

        var url = window.location.href.match(/view([0-9]*)/);
        $scope.env.adv_id = url!=null && url[1]!=undefined ?  url[1]*1 : null;

        url = window.location.href.match(/\/page([0-9]*)/);
        $scope.env.page = url!=null && url[1]!=undefined ?  url[1]*1 : $scope.env.page;

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
            if ($scope.env.search.config && $scope.env.search.config.display_map){
                $scope.displayMap($scope.env.search.config.display_map, false);
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
            $scope.setPage($scope.env.page)
        });

        $scope.changePerPage = function (value) {
            $scope.env.per_page = value*1;
            $scope.env.page = value*1;

            $scope.setPage(1);

            updateSearch();
        };

        function updatePagination(){
            var pages = Math.round($scope.env.rows.length/$scope.env.per_page);
            $scope.env.pages = pages>1 ? pages : null;

            $scope.env.current = $scope.env.rows.slice( ($scope.env.page-1)*$scope.env.per_page, (($scope.env.page-1)*$scope.env.per_page) + $scope.env.per_page)

        };

        $scope.range = function(min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $scope.setPage = function(page){
            window.location.href = "/search/"+$scope.env.result_id+"#/page"+page;
            $scope.env.page = page;
            updatePagination();
        };

        $scope.displayMap = function(flag, need_update){
            $scope.env.display_map = flag;
            if (need_update){
                updateSearch();
            }
            if ( $scope.env.display_map === true ){
                initGoogleMaps()
            }
        };

        function updateSearch() {
            $scope.env.search.update(
                {
                    per_page: $scope.env.per_page,
                    sortby: $scope.env.sortby,
                    display_map: $scope.env.display_map
                }
            );
        };
        
        function initGoogleMaps() {

            var interval = $interval(function(){
                if ( document.getElementById('map') ){
                    $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: -34.397, lng: 150.644},
                        zoom: 8
                    });
                    $interval.cancel(interval);
                }
            },1000)

        }

        $scope.page_loaded = function () {
            console.log(1)
        }
    }
})();


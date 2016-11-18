(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultListingController', searchResultListingController);

    searchResultListingController.$inject = ['$scope', 'advFactory','$q','$interval','$filter'];

    function searchResultListingController($scope, advFactory,$q,$interval,$filter) {

        $scope.env = {
            loading: true,
            per_page: 20,
            sortby: 'date_create',
            page: 1,
            pages: null,
            current:[],
            display_map: false,
            map: null
        };

        var url = window.location.href.match(/\/page([0-9]*)/);
        $scope.env.page = url!=null && url[1]!=undefined ?  url[1]*1 : $scope.env.page;

        var advPromise = advFactory.getResult($scope.root.result_id, {}).then( function(response){
            $scope.env.rows= response.advs;
            $scope.env.city= response.city;
            //console.log(response.advs)
        });
        $scope.promises.push(advPromise);

        $q.all($scope.promises).then(function () {

            $scope.env.loading = false;
             $scope.setPage($scope.env.page);

             if ($scope.root.search.config && $scope.root.search.config.per_page){
                $scope.changePerPage($scope.root.search.config.per_page)
            }
             if ($scope.root.search.config && $scope.root.search.config.sortby){
                $scope.changeSort($scope.root.search.config.sortby)
            }
            if ($scope.root.search.config && $scope.root.search.config.display_map){
                $scope.displayMap($scope.root.search.config.display_map, false);
            }
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

            $scope.env.current = $scope.env.rows.slice( ($scope.env.page-1)*$scope.env.per_page, (($scope.env.page-1)*$scope.env.per_page) + $scope.env.per_page);
            window.scrollTo(0, 0);
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
            window.location.href = "/search/"+$scope.root.result_id+"#/page"+page;
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
            $scope.root.search.update(
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

        $scope.changeSort = function (value) {
            $scope.env.sortby = value;
            if ( value=='date_create' ){
                $scope.env.rows = $filter('orderBy')($scope.env.rows,'-created_at');
            }else if( value=='price_up' ){
                $scope.env.rows = $filter('orderBy')($scope.env.rows,'-cold_rent', true);
            }else if( value=='price_down' ){
                $scope.env.rows = $filter('orderBy')($scope.env.rows,'-cold_rent' );
            }
            updatePagination();
            updateSearch();
        };

        $scope.addToFav = function (adv, flag) {

            if (flag===true){
                adv.addToFavList($scope.user);
                alertify.success( 'Adv added to watchlist' );
            }else{
                adv.deleteFromFavList($scope.user);
                alertify.success( 'Adv removed from watchlist' );
            }
        }

    }
})();


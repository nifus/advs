(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultController', searchResultController);

    searchResultController.$inject = ['$scope', 'searchLogFactory', '$q', '$interval', '$filter','advFactory' ,'$cookies'];

    function searchResultController($scope, searchLogFactory, $q, $interval, $filter, advFactory, $cookies) {

        $scope.adv = null;
        $scope.message = {

        };
        $scope.env = {
            adv_id: null,
            result_id: null,
            search: null,
            loading: true,
            per_page: 20,
            sortby: 'date_create',
            page: 1,
            pages: null,
            current: [],
            display_map: false,
            map: null,
            submit: false

        };
        $scope.promises = [];


        $scope.openAdv = function(adv){
            window.location.href = "/search/"+$scope.env.result_id+"#/view"+adv;
            $scope.env.adv_id = adv;
            advFactory.getById($scope.env.adv_id).then( function(response){
                $scope.adv=response;
            });
            initGoogleMaps();
        };
        $scope.changePerPage = function (value) {
            $scope.env.per_page = value * 1;
            $scope.env.page = value * 1;
            $scope.setPage(1);
            updateSearch();
        };

        function updatePagination() {
            var pages = Math.round($scope.env.rows.length / $scope.env.per_page);
            $scope.env.pages = pages > 1 ? pages : null;

            $scope.env.current = $scope.env.rows.slice(($scope.env.page - 1) * $scope.env.per_page, (($scope.env.page - 1) * $scope.env.per_page) + $scope.env.per_page);
            window.scrollTo(0, 0);
        };

        $scope.range = function (min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $scope.setPage = function (page) {
            window.location.href = "/search/" + $scope.env.result_id + "#/page" + page;
            $scope.env.page = page;
            updatePagination();

        };

        $scope.displayMap = function (flag, need_update) {
            $scope.env.display_map = flag;
            if (need_update) {
                updateSearch();
            }
            if ($scope.env.display_map === true) {
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

            var interval = $interval(function () {
                if (document.getElementById('map')) {
                    $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: -34.397, lng: 150.644},
                        zoom: 8
                    });
                    $interval.cancel(interval);
                }
            }, 1000)

        }

        $scope.changeSort = function (value) {
            $scope.env.sortby = value;
            if (value == 'date_create') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-created_at');
            } else if (value == 'price_up') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-cold_rent', true);
            } else if (value == 'price_down') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-cold_rent');
            }
            updatePagination();
            updateSearch();
        };

        $scope.addToFav = function (adv, flag) {

            if (flag === true) {
                adv.addToFavList($scope.user);
                alertify.success('Adv added to watchlist');
            } else {
                adv.deleteFromFavList($scope.user);
                alertify.success('Adv removed from watchlist');
            }
        }

        ////
        $scope.favlist = function (flag) {
            if (flag===true){
                $scope.adv.addToFavList($scope.user);
                alertify.success( 'Adv added to watchlist' );
            }else{
                $scope.adv.deleteFromFavList($scope.user);
                alertify.success( 'Adv removed from watchlist' );
            }
        };

        $scope.displayMap = function () {
            $scope.env.display_map = true;

        };

        $scope.displayPhotos = function () {
            $scope.env.display_map = false;
        };

        $scope.sendMessage = function (form, data) {
            $scope.env.submit = true;
            if ( form.$invalid ){
                return false;
            }
            $scope.adv.sendMessage(data).then(function(response){
                var expireDate = new Date();
                expireDate.setDate(expireDate.getDate() + 199);
                $cookies.putObject('contact',{
                    name:data.name,
                    sex: data.sex,
                    email: data.email,
                    phone: data.phone
                }, {expires:expireDate});
                $scope.env.submit = false;
                if (response.success){
                    alertify.success( 'Message send to owner adv' );
                    $scope.message = {};
                }else{
                    alertify.error( response.error );
                }
            })
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

        function restoreContactData() {
            var data = $cookies.getObject('contact');
            $scope.message = data;
        }


        $scope.$watch(function(){return window.location.href},function (value) {
            var url = value.match(/view([0-9]*)/);
            if( url == null ){
                $scope.env.adv_id = undefined
            }
        })

        $scope.goBack = function () {

            window.history.back()
        }
        function init() {
            var url = window.location.href.match(/view([0-9]*)/);
            $scope.env.adv_id = url != null && url[1] != undefined ? url[1] * 1 : null;

            url = window.location.href.match(/\/search\/([0-9]*)/);
            $scope.env.result_id = url != null && url[1] != undefined ? url[1] * 1 : null;

            url = window.location.href.match(/\/page([0-9]*)/);
            $scope.env.page = url != null && url[1] != undefined ? url[1] * 1 : $scope.env.page;
            if ($scope.env.adv_id ){
                $scope.openAdv($scope.env.adv_id)

                var searchPromise = searchLogFactory.getById($scope.env.result_id).then(function (response) {
                    $scope.env.search = response;
                });
                $scope.promises.push(searchPromise);

                var advPromise = advFactory.getResult($scope.env.result_id, {}).then(function (response) {
                    $scope.env.rows = response.advs;
                    $scope.env.city = response.city;
                });
                $scope.promises.push(advPromise);



                $q.all($scope.promises).then(function () {
                    $scope.env.loading = false;
                });

            }else{
                var searchPromise = searchLogFactory.getById($scope.env.result_id).then(function (response) {
                    $scope.env.search = response;
                });
                $scope.promises.push(searchPromise);

                var advPromise = advFactory.getResult($scope.env.result_id, {}).then(function (response) {
                    $scope.env.rows = response.advs;
                    $scope.env.city = response.city;
                });
                $scope.promises.push(advPromise);



                $q.all($scope.promises).then(function () {

                    $scope.env.loading = false;
                    $scope.setPage($scope.env.page);

                    if ($scope.env.search.config && $scope.env.search.config.per_page) {
                        $scope.changePerPage($scope.env.search.config.per_page)
                    }
                    if ($scope.env.search.config && $scope.env.search.config.sortby) {
                        $scope.changeSort($scope.env.search.config.sortby)
                    }
                    if ($scope.env.search.config && $scope.env.search.config.display_map) {
                        $scope.displayMap($scope.env.search.config.display_map, false);
                    }
                });
            }



        }
        init();
    }
})();


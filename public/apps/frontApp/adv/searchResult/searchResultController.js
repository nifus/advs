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
            display_view_map: false,
            map: null,
            submit: false,
            rows: null,
        };
        $scope.promises = [];



        $scope.changePerPage = function (value, page, need_update) {
            $scope.env.per_page = value * 1;
            if (page==undefined){
                $scope.env.page = value * 1;
                $scope.setPage(1);
            }
            if ( need_update!=undefined && need_update==true){
                updateSearch();
            }
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
            if ( $scope.env.page==1 && page==1 ){

            }else{
                window.location.href = "/search/" + $scope.env.result_id + "#/page" + page;
            }
            $scope.env.page = page;
            updatePagination();
        };

        $scope.displayMap = function (flag, need_update) {
            $scope.env.display_map = flag;
            $scope.env.display_view_map = flag;
            if (need_update) {
                updateSearch();
            }
            if ($scope.env.display_map === true) {
                if ($scope.env.adv_id){
                    initGoogleMapsView();
                }else{
                    initGoogleMapsListing();
                }

            }
        };

        $scope.changeSort = function (value, need_update) {
            $scope.env.sortby = value;
            if (value == 'date_create') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-created_at');
            } else if (value == 'price_up') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-cold_rent', true);
            } else if (value == 'price_down') {
                $scope.env.rows = $filter('orderBy')($scope.env.rows, '-cold_rent');
            }
            updatePagination();

            if ( need_update!=undefined && need_update==true){
                updateSearch();
            }
        };

        $scope.addToFav = function (adv, flag) {
            if (flag === true) {
                console.log($scope.user)
                adv.addToFavList($scope.user);
                alertify.success('Adv added to watchlist');
            } else {
                adv.deleteFromFavList($scope.user);
                alertify.success('Adv removed from watchlist');
            }
        }

        function updatePagination() {
            if ($scope.env.rows==null){
                return;
            }
            var pages = Math.round($scope.env.rows.length / $scope.env.per_page);
            $scope.env.pages = pages > 1 ? pages : null;

            $scope.env.current = $scope.env.rows.slice(($scope.env.page - 1) * $scope.env.per_page, (($scope.env.page - 1) * $scope.env.per_page) + $scope.env.per_page);
            window.scrollTo(0, 0);
        };

        function updateSearch() {
            $scope.env.search.update(
                {
                    per_page: $scope.env.per_page,
                    sortby: $scope.env.sortby,
                    display_map: $scope.env.display_map,
                    zoom: $scope.env.zoom,
                    lat: $scope.env.lat,
                    lng: $scope.env.lng
                }
            )
        };




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



        $scope.displayPhotos = function () {
            $scope.env.display_view_map = false;
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

        $scope.$watch(function(){return window.location.href},function (value) {

            var url = value.match(/view([0-9]*)/);
            $scope.env.adv_id = url != null && url[1] != undefined ? url[1] * 1 : null;

            url = value.match(/\/search\/([0-9]*)/);
            $scope.env.result_id = url != null && url[1] != undefined ? url[1] * 1 : null;

            url = value.match(/\/page([0-9]*)/);
            $scope.env.page = url != null && url[1] != undefined ? url[1] * 1 : $scope.env.page;


            if( $scope.env.adv_id == null ){
                initListing();
            }else{
                initView();
            }
        });

        $scope.goBack = function () {
            if ( $scope.env.rows ){
                window.history.back()
            }else{
                window.location.href='/search/'+$scope.env.result_id;
            }
        };


        function initGoogleMapsListing() {
                var interval = $interval(function(){

                    if ( document.getElementById('map') ){
                        if ( $scope.env.zoom!=null ){

                            $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                                center: {lat: $scope.env.lat, lng: $scope.env.lng},
                                zoom:  $scope.env.zoom
                            });
                        }else{

                            $scope.env.map = new google.maps.Map(document.getElementById('map'), {
                                center: {lat: $scope.env.search.query.lat*1, lng: $scope.env.search.query.lng*1},
                                zoom: 15
                            });
                        }
                        $scope.env.map.addListener('zoom_changed', function() {
                            $scope.env.zoom = $scope.env.map.getZoom();
                            updateSearch();
                        });
                        $scope.env.map.addListener('center_changed', function(e, val) {
                            var center = $scope.env.map.getCenter();
                            $scope.env.lat = center.lat();
                            $scope.env.lng = center.lng();
                            updateSearch();
                        });
                        var markers = [];
                        var bounds = new google.maps.LatLngBounds();
                        var infowindow = null;
                        for( var i in $scope.env.rows ){
                            var marker = new google.maps.Marker({
                                position: {lat: $scope.env.rows[i].lat*1, lng: $scope.env.rows[i].lng*1},
                            });
                            marker.adv = $scope.env.rows[i];
                            marker.addListener('click', function (e) {
                                if ( infowindow!=null ){
                                    infowindow.close()
                                }
                                 infowindow = new google.maps.InfoWindow({
                                    content: getAdvContent(this.adv)
                                });
                                infowindow.open($scope.env.map, this);
                            });
                            markers.push(marker);
                            bounds.extend(marker.getPosition());
                        }

                        if ( $scope.env.zoom==null ){
                            $scope.env.map.fitBounds(bounds);
                        }
                        $scope.env.zoom = $scope.env.map.getZoom();
                       // console.log($scope.env.zoom)
                        var center = $scope.env.map.getCenter();
                        $scope.env.lat = center.lat();
                        $scope.env.lng = center.lng();
                        updateSearch();

                         new MarkerClusterer($scope.env.map, markers);

                        $interval.cancel(interval);
                    }
                },1000)

        }

        function initGoogleMapsView() {

                var interval = $interval(function(){
                    if ( document.getElementById('view_map') ){
                        var map = new google.maps.Map(document.getElementById('view_map'), {
                            center: {lat: $scope.adv.lat*1, lng: $scope.adv.lng*1},
                            zoom: 15
                        });
                        new google.maps.Marker({
                            position: {lat: $scope.adv.lat*1, lng: $scope.adv.lng*1},
                            map: map
                        });
                        // $scope.env.map.setCenter( {lat: $scope.adv.lat*1, lng: $scope.adv.lng*1} )
                        $interval.cancel(interval);
                    }
                },1000)

        }

        function restoreContactData() {
            var data = $cookies.getObject('contact');
            $scope.message = data;
        }

        //
        function initListing() {
            console.log('initListing')

            $scope.env.loading = true;
            $scope.promises = [];
            if ( $scope.env.search==null ) {
                var searchPromise = searchLogFactory.getById($scope.env.result_id).then(function (response) {
                    $scope.env.search = response;

                    if (response.config && response.config.zoom){
                        $scope.env.zoom = response.config.zoom;
                        $scope.env.lng = response.config.lng;
                        $scope.env.lat = response.config.lat;
                    }

                });
                $scope.promises.push(searchPromise);
            }

            if ( $scope.env.rows==null){
                var advPromise = advFactory.getResult($scope.env.result_id, {}).then(function (response) {
                    $scope.env.rows = response.advs;
                    $scope.env.city = response.city;
                });
                $scope.promises.push(advPromise);
            }

            $q.all($scope.promises).then(function () {
                console.log('promise end')
                $scope.env.loading = false;
                console.log($scope.env.search)
                if ($scope.env.search.config && $scope.env.search.config.display_map) {
                    $scope.displayMap($scope.env.search.config.display_map, false);
                }

                $scope.setPage($scope.env.page);

                if ($scope.env.search.config && $scope.env.search.config.per_page) {
                    $scope.changePerPage($scope.env.search.config.per_page , $scope.env.page)
                }
                if ($scope.env.search.config && $scope.env.search.config.sortby) {
                    $scope.changeSort($scope.env.search.config.sortby)
                }

            });
        }

        function initView() {
            $scope.env.loading = true;
            $scope.env.display_view_map = false;
            var advPromise = advFactory.getById($scope.env.adv_id).then( function(response){
                $scope.adv=response;
            });

            $scope.promises.push(advPromise);

            if ( $scope.env.search==null ){
                var searchPromise = searchLogFactory.getById($scope.env.result_id).then(function (response) {
                    $scope.env.search = response;
                });
                $scope.promises.push(searchPromise);
                //initGoogleMaps();
            }


            $q.all($scope.promises).then(function () {
                $scope.env.loading = false;
            });
        }

        function getAdvContent(adv) {
            return '<div class="media"> ' +
                '<div class="media-left"> ' +
                '<a href="/search/'+$scope.env.result_id+'#/view'+adv.id+'"><img class="media-object" style="width:150px" src="'+adv.photos[0]+'" "></a> ' +
                '</div> ' +
                '<div class="media-body"> ' +
                '<h4 class="media-heading"><a href="/search/'+$scope.env.result_id+'#/view'+adv.id+'">'+adv.title+'</a></h4> ' +
                '</div> ' +
                '</div>';

        }


    }
})();


(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultViewController', searchResultViewController);

    searchResultViewController.$inject = ['$scope', 'advFactory','$q','$interval'];

    function searchResultViewController($scope, advFactory, $q, $interval) {

        $scope.env = {
            loading: true,
            adv_id: null,
            display_map: false
        };
        $scope.adv = null;

        var url = window.location.href.match(/\/view([0-9]*)/);
        $scope.env.adv_id = url!=null && url[1]!=undefined ?  url[1]*1 : $scope.env.adv_id;


        var advPromise = advFactory.getById($scope.env.adv_id).then( function(response){
            $scope.adv=response;
        });
        $scope.promises.push(advPromise);

        $q.all($scope.promises).then(function () {
            $scope.env.loading = false;
            initGoogleMaps();
        });


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

    }
})();


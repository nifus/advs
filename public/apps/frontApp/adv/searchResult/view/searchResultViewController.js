(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultViewController', searchResultViewController);

    searchResultViewController.$inject = ['$scope', 'advFactory','$q','$interval','$cookies',''];

    function searchResultViewController($scope, advFactory, $q, $interval, $cookies) {
        console.log('searchResultViewController')
        $scope.env = {
            loading: true,
            adv_id: null,
            display_map: false,
            submit: false
        };
        $scope.adv = null;
        $scope.message = {};






        var advPromise = advFactory.getById($scope.env.adv_id).then( function(response){
            $scope.adv=response;
        });
        $scope.promises.push(advPromise);

        $q.all($scope.promises).then(function () {
            $scope.env.loading = false;
            initGoogleMaps();

            restoreContactData();
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

    }
})();


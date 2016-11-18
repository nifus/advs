(function () {
    'use strict';
    angular.module('frontApp').controller('searchResultViewController', searchResultViewController);

    searchResultViewController.$inject = ['$scope', 'advFactory','$q'];

    function searchResultViewController($scope, advFactory, $q) {

        $scope.env = {
            loading: true,
            adv_id: null,
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
        });


        $scope.favlist = function (flag) {
            if (flag===true){
                $scope.adv.addToFavList($scope.user);
                alertify.success( 'Adv added to watchlist' );
            }else{
                $scope.adv.deleteFromFavList($scope.user);
                alertify.success( 'Adv removed from watchlist' );
            }
        }

    }
})();


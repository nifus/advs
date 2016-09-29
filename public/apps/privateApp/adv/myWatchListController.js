(function () {
    'use strict';
    angular.module('privateApp').controller('myWatchListController', myWatchListController);

    myWatchListController.$inject = ['$scope', 'userFactory', '$filter', '$q'];

    function myWatchListController($scope, userFactory, $filter, $q) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            advs: {
                rent:[1],
                sell:[]
            },
            loading: true,
            news: [],
            filters:{
                type: 'all'
            },
            order: 'created_down',
            current_rent: 1,
            current_sell: 1
        };


        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            var advPromise = $scope.user.getMyWatchAdvs().then(function (result) {
                result = $filter('orderBy')(result,'-created_at');


                $scope.env.advs.rent = $filter('filter')(result,{type:'rent'})
                $scope.env.advs.rent = $filter('filter')(result,{type:'sell'})
                console.log($scope.env.advs)
            });

            $q.all([ advPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);

        $scope.deleteItem = function(item){
            item.deleteFromWatchList().then( function(){
                $scope.env.advs.rent = $scope.env.advs.rent.filter( function(adv){
                    if ( adv.id!=item.id){
                        return true;
                    }
                })
                $scope.env.advs.sell = $scope.env.advs.sell.filter( function(adv){
                    if ( adv.id!=item.id){
                        return true;
                    }
                })
            })
        };

        $scope.$watch('env.order', function(value){
            switch(value){
                case('price_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-total_rent',1);
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-total_rent',1);
                    break;
                case('price_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-total_rent');
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-total_rent');

                    break;
                case('created_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-created_at',1);
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-created_at',1);
                    break;
                case('created_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-created_at');
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-created_at');

                    break;
            }

        })


    }
})();


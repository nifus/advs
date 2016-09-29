(function () {
    'use strict';
    angular.module('privateApp').controller('myController', myController);

    myController.$inject = ['$scope', 'userFactory', 'newsFactory', '$q', '$filter'];

    function myController($scope, userFactory, newsFactory, $q, $filter) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            advs: [],
            loading: true,
            news: [],
            stat: {
                rent: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                },
                sell: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                }
            },
            filters:{
                type: 'all'
            },
            order: 'created_down'
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            var newsPromise = null;
            if ($scope.user.isPrivateAccount()) {
                newsPromise = newsFactory.getLastPrivateNews();
            } else {
                newsPromise = newsFactory.getLastBusinessNews();
            }

            newsPromise.then(function (news) {
                $scope.env.news = news;
            });

            var statPromise = $scope.user.getAdvStat().then(function (result) {
                $scope.env.stat = result;
            });

            var advPromise = $scope.user.getAdvs().then(function (result) {
                result = $filter('orderBy')(result,'-created_at');

                $scope.env.advs = {
                    rent: $filter('filter')(result,{type:'rent'}),
                    sell: $filter('filter')(result,{type:'sell'})
                };
            });

            $q.all([statPromise, newsPromise, advPromise]).then(function () {
                $scope.env.loading = false;
            })
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.logout = function () {
            userFactory.logout();
            window.location.reload(true)
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


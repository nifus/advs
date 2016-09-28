(function () {
    'use strict';
    angular.module('privateApp').controller('myWatchListController', myWatchListController);

    myWatchListController.$inject = ['$scope', 'userFactory', 'newsFactory', '$q'];

    function myWatchListController($scope, userFactory, newsFactory, $q) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            advs: [],
            loading: true,
            news: [],
            filters:{
                type: 'all'
            },
            order: 'price_up'
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            var advPromise = $scope.user.getMyWatchAdvs().then(function (result) {
                $scope.env.advs = result;
                console.log(result)
            });

            $q.all([ advPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);




    }
})();


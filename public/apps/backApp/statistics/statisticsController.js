(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('statisticsController', ['$scope', 'userFactory', '$q', '$state','advFactory',statisticsController]);


    function statisticsController($scope, userFactory, $q, $state, advFactory) {
        $scope.env = {
            users: [],
            user: null,
            reload_accounts: false,
            reload_adverts: false,
            user_stat: null,
            adv_stat: null
        }

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if ( !$scope.user.hasPermission('statistics')) {
                $state.go('adverts-search');
                return;
            }
            var users_promise = userFactory.getStatistics().then(function (response) {
                $scope.env.user_stat = response;
            });
            var adv_promise = advFactory.getStatistics().then(function (response) {
                console.log(response)
                $scope.env.adv_stat = response;
            });
            $q.all([users_promise, adv_promise]).then(function () {
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);


        $scope.reloadAccounts = function () {
            $scope.env.reload_accounts = true;
            userFactory.getStatistics().then(function (response) {
                $scope.env.user_stat = response;
                $scope.env.reload_accounts = false;
            });
        }
        $scope.reloadAdverts = function () {
            $scope.env.reload_adverts = true;
            userFactory.getStatistics().then(function (response) {
                $scope.env.adv_stat = response;
                $scope.env.reload_adverts = false;
            });
        }
    }

})();
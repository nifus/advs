(function () {
    'use strict';
    angular.module('privateApp').controller('dashboardController', dashboardController);

    dashboardController.$inject = ['$scope', 'userFactory', 'newsFactory', '$q', '$window', '$filter'];

    function dashboardController($scope, userFactory, newsFactory, $q, $window, $filter) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
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
                sale: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                }
            }
        };

        function initPage(deferred) {
            $window.document.title = $filter('translate')('Dashboard');
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

            $q.all([statPromise, newsPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.logout = function () {
            userFactory.logout();
            window.location.reload(true)
        };


    }
})();


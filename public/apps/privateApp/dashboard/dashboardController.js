(function () {
    'use strict';
    angular.module('privateApp').controller('dashboardController', ['$scope', 'userFactory', 'faqFactory', '$q', '$window', '$filter',dashboardController]);


    function dashboardController($scope, userFactory, faqFactory, $q, $window, $filter) {
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

            var announcementsPromise = null;
            if ($scope.user.isPrivateAccount()) {
                announcementsPromise = faqFactory.getPrivateAnnouncements();
            } else {
                announcementsPromise = faqFactory.getBusinessAnnouncements();
            }

            announcementsPromise.then(function (news) {
                $scope.env.announcements = news;
            });

            var statPromise = $scope.user.getAdvStat().then(function (result) {
                $scope.env.stat = result;
            });

            $q.all([statPromise, announcementsPromise]).then(function () {
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


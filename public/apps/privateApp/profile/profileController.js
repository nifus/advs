(function () {
    'use strict';
    angular.module('privateApp').controller('profileController', ['$scope', 'userFactory', 'faqFactory', '$q', '$window', '$filter',profileController]);


    function profileController($scope, userFactory, faqFactory, $q, $window, $filter) {
        $scope.promises = null;
        $scope.env = {
            loading: true,

        };

        function initPage(deferred) {
            $window.document.title = $filter('translate')('My profile');

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


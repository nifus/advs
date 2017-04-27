(function () {
    'use strict';
    angular.module('privateApp').controller('profileController', ['$scope', 'userFactory', 'faqFactory', '$q', '$window', '$filter',profileController]);


    function profileController($scope, userFactory, faqFactory, $q, $window, $filter) {
        $scope.promises = null;
        $scope.env = {
            loading: true,
            specialization: null,
            service: null,
        };
        $scope.model = {
            specializations: {
                common:[],
                personal:[]
            },
            services:{
                common:[],
                personal:[]
            }
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


        $scope.addSpecialization = function () {
            if ($scope.env.specialization==null){
                return;
            }
            $scope.model.specializations.personal.push($scope.env.specialization);
            $scope.env.specialization = null;
        };
        $scope.addService = function () {
            if ($scope.env.service==null){
                return;
            }
            $scope.model.services.personal.push($scope.env.service);
            $scope.env.service = null;
        };


    }
})();


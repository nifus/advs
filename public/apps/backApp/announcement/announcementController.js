(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('announcementController', announcementController);

    announcementController.$inject = ['$scope', 'configFactory', '$q', '$filter'];

    function announcementController($scope, configFactory, $q, $filter) {
        $scope.model = {
            private: {
                status: '1'
            },
            business: {
                status: '1'
            }
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            var config_promise = configFactory.get().then(function (response) {
                if (response.announcement) {
                    $scope.model = response.announcement
                }
                console.log($scope.model.announcement)
            });
            $q.all([config_promise]).then(function () {
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);


        $scope.saveAnnouncement = function (type) {
            configFactory.saveAnnouncement(type, $scope.model[type]).then(function (response) {
                console.log(response);
                alertify.success($filter('translate')('Announcement updated'));
            })
        };

        $scope.$watch('model.private.status', function (value) {
            if ( value=='0' ){
                $scope.model.private.text = $filter('translate')('At the moment there are no errors or news');
            }
        });

        $scope.$watch('model.business.status', function (value) {
            if ( value=='0' ){
                $scope.model.business.text = $filter('translate')('At the moment there are no errors or news');
            }
        });
    }

})();
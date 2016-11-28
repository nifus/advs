(function () {
    'use strict';
    angular.module('privateApp').controller('subscriptionController', subscriptionController);

    subscriptionController.$inject = ['$scope', '$state', '$filter', '$q', '$window', '$http'];

    function subscriptionController($scope, $state, $filter, $q, $window, $http) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            loading: false,
            tariffs: []
        };


        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;
            $window.document.title = $filter('translate')('Subscription');

            var tariffPromise = $http.get('/api/tariffs').then(function (response) {
                $scope.env.tariffs = response.data;
            });

            var statPromise = $scope.user.getAdvStat().then(function (result) {
                $scope.env.stat = result;
            });

            $q.all([tariffPromise, statPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


    }
})();


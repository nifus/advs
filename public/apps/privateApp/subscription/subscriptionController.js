(function () {
    'use strict';
    angular.module('privateApp').controller('subscriptionController', subscriptionController);

    subscriptionController.$inject = ['$scope', '$state', '$filter', '$q','$window'];

    function subscriptionController($scope, $state, $filter, $q,$window) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            loading: false,
        };



        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;
            $window.document.title = $filter('translate')('Subscription');

            $scope.payment_form = $scope.user;
            $scope.contact_form = $scope.user;

            $q.all([]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);





    }
})();


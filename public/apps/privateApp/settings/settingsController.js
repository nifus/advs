(function () {
    'use strict';
    angular.module('privateApp').controller('settingsController', settingsController);

    settingsController.$inject = ['$scope', 'userFactory', '$filter', '$q'];

    function settingsController($scope, userFactory, $filter, $q) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            loading: false,
        };

        $scope.email_form = {
            submit: false
        };
        $scope.password_form = {
            submit: false
        };


        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;
            $q.all([]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);



        $scope.changeEmail = function(data){
            data.submit = true;
            data.error = undefined;
            if (!$scope.email.$invalid) {
                $scope.user.changeEmail(data).success(function () {
                    $scope.user.email = data.email;
                    $scope.email_form = {submit: false};
                    alertify.success("Email changed");
                }).error(function (response) {
                    data.error = response.error;
                })
            }
        };

        $scope.changeAllowNotifications = function(value){
            $scope.user.changeAllowNotifications(value).error(function (response) {
                alertify.error(  response.error );
            })
        };

        $scope.changePassword = function(data){
            data.submit = true;
            data.error = undefined;
            if (!$scope.password.$invalid) {
                $scope.user.changePassword(data).success(function () {
                   // $scope.user.email = data.email;
                    $scope.password_form = {submit: false};
                    alertify.success("Password changed");
                }).error(function (response) {
                    data.error = response.error;
                })
            }
        };

    }
})();


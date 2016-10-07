(function () {
    'use strict';
    angular.module('privateApp').controller('settingsController', settingsController);

    settingsController.$inject = ['$scope', '$state', '$filter', '$q'];

    function settingsController($scope, $state, $filter, $q) {
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
        $scope.payment_form = {
            submit: false,
            payment_type: null
        };


        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            $scope.payment_form.payment_type = $scope.user.payment_type;
            $scope.payment_form.paypal_email = $scope.user.paypal_email;
            $scope.payment_form.giro_account = $scope.user.giro_account;
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

        $scope.changePayment = function(data){
            data.submit = true;
            data.error = undefined;
            //console.log(data)
            if (!$scope.payment.$invalid) {
                $scope.user.changePayment(data).success(function () {
                    $scope.user.payment_type = data.payment_type;
                    $scope.user.paypal_email = data.paypal_email;
                    $scope.user.giro_account = data.giro_account;
                    $scope.payment_form.submit = false;
                    $scope.payment_form.error = undefined;
                    alertify.success("Payment changed");
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

        $scope.deleteAccount = function(){
            alertify.confirm("Are you sure you want to delete your account?", function (e) {
                if (e) {
                   $scope.user.deleteAccount().success(function(){
                       $state.go('delete-account')
                   }).error(function(error){
                       alertify.error( response.error );
                   })
                }
            });
        }

    }
})();


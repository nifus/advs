(function () {
    'use strict';
    angular.module('privateApp').controller('settingsController', ['$scope', '$state', '$filter', '$q', '$window', settingsController]);


    function settingsController($scope, $state, $filter, $q, $window) {
        $scope.promises = null;
        $scope.env = {
            loading: false,
        };

        $scope.email_form = {
            submit: false,
            is_confirmed: false
        };
        $scope.password_form = {
            submit: false
        };
        $scope.payment_form = {
            submit: false,
            payment_type: null
        };
        $scope.contact_form = {
            submit: false,
        };
        $scope.autocomplete = {};

        $scope.$watch('autocomplete', function (value) {
            if (value.details && value.details.address_components.length == 7) {
                $scope.contact_form.address_street = value.details.address_components[1].short_name;
                $scope.contact_form.address_number = value.details.address_components[0].short_name;
                $scope.contact_form.address_zip = value.details.address_components[6].short_name;
                $scope.contact_form.address_city = value.details.vicinity;
            }
        }, true);

        function initPage(deferred) {
            $window.document.title = $filter('translate')('Settings');


            $scope.payment_form = $scope.user;
            $scope.contact_form = $scope.user;


            $scope.env.loading = false;

            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.changeEmail = function (data) {
            data.submit = true;
            data.error = undefined;
            if (!$scope.email.$invalid) {
                $scope.user.changeEmail(data).then(function () {
                    $scope.user.email = data.email;
                    $scope.email_form = {submit: false, is_confirmed: false};
                    alertify.success($filter('translate')("Email changed"));
                }, function (response) {
                    data.error = response.error;
                })
            }
        };

        $scope.sendConformCode = function (data) {
            data.submit = true;
            data.error = undefined;
            if (!$scope.email.$invalid) {
                $scope.user.sendConfirmCode(data.email).then(function () {
                    data.is_confirmed = true;
                    data.submit = false;

                }, function (response) {
                    alertify.error(response.error);
                })
            }
        };

        $scope.changeContactData = function (data) {
            data.submit = true;
            data.error = undefined;

            if (!$scope.contact.$invalid) {

                $scope.user.changeContactData(data).then(function () {
                    $scope.contact_form.submit = false;
                    $scope.contact_form.error = undefined;
                    alertify.success($filter('translate')('Contact Data Changed'));
                }, function (response) {
                    alertify.error(response.error);

                })
            }
        };

        $scope.changePayment = function (data) {
            data.submit = true;
            data.error = undefined;
            //console.log(data)
            if (!$scope.payment.$invalid) {
                $scope.user.changePayment(data).then(function () {
                    $scope.user.payment_type = data.payment_type;
                    $scope.user.paypal_email = data.paypal_email;
                    $scope.user.giro_account = data.giro_account;
                    $scope.payment_form.submit = false;
                    $scope.payment_form.error = undefined;
                    alertify.success($filter('translate')("Payment changed"));
                }, function (response) {
                    data.error = response.error;
                })
            }
        };

        $scope.changeAllowNotifications = function (value) {
            $scope.user.changeAllowNotifications(value).then(function () {

            }, function (response) {
                alertify.error(response.error);
            })
        };

        $scope.changePassword = function (data) {
            data.submit = true;
            data.error = undefined;
            if (!$scope.password.$invalid) {
                $scope.user.changePassword(data).then(function () {
                    // $scope.user.email = data.email;
                    $scope.password_form = {submit: false};
                    alertify.success($filter('translate')("Password changed"));
                }, function (response) {
                    data.error = response.error;
                })
            }
        };

        $scope.deleteAccount = function () {
            alertify.confirm($filter('translate')("Are you sure you want to delete your account?"), function (e) {
                if (e) {
                    $scope.user.deleteAccount().then(function () {
                        $state.go('delete-account')
                    }, function (error) {
                        alertify.error(response.error);
                    })
                }
            });
        }

    }
})();


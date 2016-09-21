(function () {
    'use strict';
    angular.module('frontApp').controller('registerController', registerController);

    registerController.$inject = ['$scope','userFactory','vcRecaptchaService'];

    function registerController($scope, userFactory, vcRecaptchaService) {

        $scope.form = {
            commercial:{

            },
            address:{

            }
        };
        $scope.submit = false;
        $scope.send = false;
        $scope.error = undefined;
        $scope.step = 'first';
        $scope.widgetId = null;
        $scope.autocomplete = {

        }

        $scope.$watch('autocomplete', function(value){
            if (value.details && value.details.address_components.length==7){
                console.log(value)
                $scope.form.address_street = value.details.address_components[1].short_name;
                $scope.form.address_number = value.details.address_components[0].short_name;
                $scope.form.address_zip = value.details.address_components[6].short_name;
                $scope.form.address_city = value.details.vicinity;

            }
        },true);

        $scope.sendRegisterForm = function(data){
            $scope.submit = true;
            if (!$scope.register.$invalid){
                $scope.send = true;
                userFactory.createPrivateAccount(data).then( function(response){
                    if (response.success==true){
                        $scope.step = 'last'
                    }else{
                        $scope.error = response.error;
                    }
                    $scope.send = false;
                    vcRecaptchaService.reload($scope.widgetId);

                });
            }
        };
        $scope.sendRegisterBusinessForm = function(data){
            $scope.submit = true;
            if (!$scope.register.$invalid){
                $scope.send = true;
                userFactory.createBusinessAccount(data).then( function(response){
                    if (response.success==true){
                        $scope.step = 'last'
                    }else{
                        $scope.error = response.error;
                    }
                    $scope.send = false;
                    vcRecaptchaService.reload($scope.widgetId);

                });
            }
        };


        $scope.setWidgetId = function (widgetId) {
            $scope.widgetId = widgetId;
        };
        $scope.cbExpiration = function() {
            vcRecaptchaService.reload($scope.widgetId);
            $scope.form.captcha = null;
        };
    }
})();


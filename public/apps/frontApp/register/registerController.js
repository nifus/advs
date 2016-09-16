(function () {
    'use strict';
    angular.module('frontApp').controller('registerController', registerController);

    registerController.$inject = ['$scope','userFactory','vcRecaptchaService'];

    function registerController($scope, userFactory, vcRecaptchaService) {

        $scope.form = {

        };
        $scope.submit = false;
        $scope.send = false;
        $scope.errors = {};
        $scope.step = 'first';
        $scope.widgetId = null;
        $scope.sendRegisterForm = function(data){
            $scope.submit = true;
            if (!$scope.register.$invalid){
                $scope.send = true;
                userFactory.createPrivateAccount(data).then( function(response){
                    if (response.success==true){
                        $scope.step = 'last'
                    }
                    $scope.send = false;
                    vcRecaptchaService.reload($scope.widgetId);

                });

            }

        };


        $scope.setWidgetId = function (widgetId) {
            console.info('Created widget ID: %s', widgetId);
            $scope.widgetId = widgetId;
        };
    }
})();


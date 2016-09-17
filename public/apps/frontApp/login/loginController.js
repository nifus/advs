(function () {
    'use strict';
    angular.module('frontApp').controller('loginController', loginController);

    loginController.$inject = ['$scope','userFactory','vcRecaptchaService'];

    function loginController($scope, userFactory, vcRecaptchaService) {

        $scope.env = {
            display_form: true,
            submit: false,
            error: false
        };
        $scope.form = {

        };

        $scope.displayLoginForm = function(){
            $scope.env.display_form = true
        };
        $scope.hideLoginForm = function(){
            $scope.env.display_form = false
        };

        $scope.loginSubmit = function(){
            $scope.env.submit = true;
            userFactory.login($scope.form).then( function(response){
                if (response.success==false){
                    $scope.env.error=response.error
                }else{
                    window.location.reload(true)
                }
                $scope.env.submit = false;

            })
        }

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


        $scope.setWidgetId = function (widgetId) {
            $scope.widgetId = widgetId;
        };
        $scope.cbExpiration = function() {
            vcRecaptchaService.reload($scope.widgetId);
            $scope.form.captcha = null;
        };
    }
})();


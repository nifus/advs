(function () {
    'use strict';
    angular.module('frontApp').controller('loginController', loginController);

    loginController.$inject = ['$scope','userFactory','vcRecaptchaService','$cookies'];

    function loginController($scope, userFactory, vcRecaptchaService, $cookies) {

        $scope.env = {
            display_form: false,
            submit: false,
            error: false,
            message: false,
            form: 'login',
            display_reset_form: true
        };
        $scope.form = {
            email: $cookies.get('email')
        };

        $scope.displayLoginForm = function(){
            $scope.env.display_form = true
        };
        $scope.hideForm = function(){
            $scope.env.display_form = false;
            $scope.env.form = 'login';
            $scope.env.message = null;
            $scope.env.error = null;
        };

        $scope.loginSubmit = function(){

            $scope.env.submit = true;
            userFactory.login($scope.form).then( function(response){
                if (response.success==false){
                    $scope.env.error=response.error
                }else{

                    if ($scope.form.remember===true){
                        var expireDate = new Date();
                        expireDate.setDate(expireDate.getDate() + 99);
                        $cookies.put('token', response.token, {'expires': expireDate});
                        $cookies.put('email', $scope.form.email, {'expires': expireDate});
                    }else{
                        $cookies.put('token', response.token);

                    }

                    window.location.reload(true);
                }
                $scope.env.submit = false;
            })
        };

        $scope.forgotSubmit = function(){
            $scope.env.submit = true;
            userFactory.forgot($scope.form.email).then( function(response){
                if (response.success==false){
                    $scope.env.error=response.error
                }else{
                    $scope.env.message=response.message;
                    $scope.env.display_reset_form = false;
                }
                $scope.env.submit = false;
            })
        };

        $scope.displayForgotForm = function(){
            $scope.env.form = 'forgot'
        };


        $scope.setWidgetId = function (widgetId) {
            $scope.widgetId = widgetId;
        };
        $scope.cbExpiration = function() {
            vcRecaptchaService.reload($scope.widgetId);
            $scope.form.captcha = null;
        };

        $scope.logout = function(){
            userFactory.logout();
            window.location.reload(true)
        }
    }
})();


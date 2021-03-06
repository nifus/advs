(function () {
    'use strict';
    angular.module('frontApp').controller('mainController', mainController);

    mainController.$inject = ['$scope', '$q', 'userFactory', '$cookies'];

    function mainController($scope, $q, userFactory, $cookies) {

        $scope.display_form = false;
        $scope.form_type = 'login';
        $scope.display_reset_form = true;


        $scope.env = {
            loading: true,
            user: null,
        };


        $scope.promises = [];
        $scope.initPromises = [];
        $scope.user = null;
        $scope.loaded = false;
        $scope.init = [];
        $scope.counter = 0;


        $scope.logout = function () {
            userFactory.logout();
            window.location.href='/'
        };
        $scope.displayForgotForm = function () {
            $scope.form_type = 'forgot'
        };
        $scope.displayLoginForm = function () {
            $scope.display_form = true;
            if ($cookies.get('email')){
                $('#login input[type=password]').focus()
            }else{
                $('#login input[type=email]').focus()
            }

        };
        $scope.hideForm = function () {
            $scope.display_form = false;
            $scope.form_type = 'login';
        };

        var userPromise = userFactory.getAuthUser().then(function (user) {
            $scope.user = user;
            $scope.env.user = user;
        },function () {
            $scope.user = null;
        });
        $scope.promises.push(userPromise);
        /*userFactory.refresh().then(function(){

         });*/

        $q.all($scope.promises).then(function () {
            // console.log('mainController loaded');
            $scope.loaded = true;
            execute();
        });


        $scope.$watchCollection('init', function (value) {
            if ($scope.loaded == true) {
                execute();
            }
        }, true);


        function execute() {

            for (var i in $scope.init) {
                var deferred = $q.defer();
                var promise = $scope.init[i](deferred, $scope.env);
                $scope.init.splice(i, 1);
                $scope.initPromises.push(promise);
            }
        }

        $scope.$watchCollection('initPromises', function (value) {
            if (value != undefined && value.length > 0) {
                $scope.loading = true;

                $scope.defer = $q.all($scope.initPromises).then(function () {
                    console.log('page loaded');
                    $scope.loading = false;
                    $scope.initPromises = [];
                });
            }

        });


    }
})();


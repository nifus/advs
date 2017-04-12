(function () {
    'use strict';
    angular.module('backApp').controller('baseController', ['$scope', '$q', 'userFactory', '$cookies','$state', baseController]);


    function baseController($scope, $q, userFactory, $cookies, $state) {

        $scope.display_form = false;
        $scope.form_type = 'login';
        $scope.display_reset_form = true;



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
            if ( !user.isAdminAccount() ){
                userFactory.logout();
                $state.go('sign_in');
                return;
            }
            $scope.setUser(user)

        },function () {
            $state.go('sign_in')
        });
        $scope.promises.push(userPromise);


        $q.all($scope.promises).then(function () {
            $scope.loaded = true;
            //console.log('loaded')
            execute();
        });





        function execute() {

            for (var i in $scope.init) {
                var deferred = $q.defer();
                var promise = $scope.init[i](deferred);
                $scope.init.splice(i, 1);
                $scope.initPromises.push(promise);
            }
        }

        $scope.$watchCollection('init', function (value) {
            if ($scope.loaded == true) {
                execute();
            }
        }, true);

        $scope.$watchCollection('initPromises', function (value) {
            if (value != undefined && value.length > 0) {
                //$scope.loaded = false;
                $q.all($scope.initPromises).then(function () {
                  //  $scope.loaded = true;
                    $scope.initPromises = [];
                });
            }
        });

        $scope.setUser = function (user) {
            $scope.user = user;
        };

        $scope.setInit = function (func) {
            $scope.init.push(func);
        }


    }
})();


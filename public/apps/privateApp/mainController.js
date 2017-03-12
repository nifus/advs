(function () {
    'use strict';
    angular.module('privateApp').controller('mainController', mainController);

    mainController.$inject = ['$scope', '$q', 'userFactory', '$state'];

    function mainController($scope, $q, userFactory, $state) {


        $scope.loading = true;
        $scope.loaded = false;

        $scope.promises = [];
        $scope.initPromises = [];
        $scope.init = [];
        $scope.counter = 0;


        var userPromise = userFactory.getAuthUser().then(function (user) {
            $scope.user = user;
            console.log(user)
            if (!user) {
                window.location.href = '/'
                return false;
            }

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
                var promise = $scope.init[i](deferred);
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

        $scope.logout = function () {
            userFactory.logout();
        }
    }
})();


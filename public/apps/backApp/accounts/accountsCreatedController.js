(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('accountsCreatedController', ['$scope', 'userFactory', '$q', '$filter',accountsCreatedController]);



    function accountsCreatedController($scope, userFactory, $q, $filter) {
        $scope.env = {
            users:[],
            selected:[],
            blocked_flag: 0,
            loading: true,
            user: null
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;
            if ( !$scope.user.hasPermission('accounts')) {
                $state.go('sign_in');
                return;
            }
            var users_promise = userFactory.getAllNewBusinessUsers().then(function (response) {
               $scope.env.users = response
            });
            $q.all([users_promise]).then(function () {
                deferred.resolve();
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

        $scope.setUser = function (user) {
            $scope.env.user = user;
            $scope.env.user.getAdvs().then(function (advs) {
                $scope.env.user.advs = advs;
            })
        };
        $scope.close = function () {
            $scope.env.user = null;
        };
        $scope.activateSelectedAccounts = function () {
            alertify.confirm($filter('translate')("Do you want to activate selected accounts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in $scope.env.selected) {
                        promise = $scope.env.selected[i].activate();
                        promises.push(promise);
                        $scope.env.users = $scope.env.users.filter(function (user) {
                            if ( user.id==$scope.env.selected[i].id){
                                return false;
                            }
                            return true;
                        })
                    }
                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected accounts is activated'));
                        $scope.env.selected = [];
                    })
                }
            });
        };

        $scope.deleteSelectedAccounts = function () {
            alertify.confirm($filter('translate')("Do you want to delete selected accounts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in $scope.env.selected) {
                        promise = $scope.env.selected[i].delete();
                        promises.push(promise);
                        $scope.env.users = $scope.env.users.filter(function (user) {
                            if ( user.id==$scope.env.selected[i].id){
                                return false;
                            }
                            return true;
                        })
                    }
                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected accounts is deleted'));
                        $scope.env.selected = [];

                    })
                }
            });
        }

    }

})();
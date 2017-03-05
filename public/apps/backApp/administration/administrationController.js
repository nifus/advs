(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('administrationController', administrationController);

    administrationController.$inject = ['$scope', 'userFactory', '$q', '$filter', '$state'];

    function administrationController($scope, userFactory, $q, $filter, $state) {
        $scope.env = {
            users: [],
            user: null,
            submit: false
        }

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if ( !$scope.user.hasPermission('administration')) {
                $state.go('dashboard');
                return;
            }
            var users_promise = userFactory.getAllAdministrationUsers().then(function (response) {
                $scope.env.users = response
            });
            $q.all([users_promise]).then(function () {
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);


        $scope.addUser = function () {
            $scope.env.user = {};
        };
        $scope.editUser = function (user) {
            $scope.env.user = user;
        };

        $scope.delete = function () {
            $scope.env.user.deleteAdministrator().then(function () {
                    for (var i in $scope.env.users) {
                        if ($scope.env.users[i].id == $scope.env.user.id) {
                            $scope.env.users.splice(i, 1);
                        }
                    }

                    $scope.cancel();
                    alertify.success($filter('translate')('Account was deleted'));
                    $scope.env.submit = false;
                }, function (error) {
                    alertify.error(error);
                    $scope.env.submit = false;
                }
            )
        }
        $scope.save = function () {
            $scope.env.submit = true;
            if ($scope.env.user.id) {
                $scope.env.user.updateAdministrator().then(function (user) {
                        $scope.cancel();
                        alertify.success($filter('translate')('Account was changed'));
                        $scope.env.submit = false;
                    }, function (error) {
                        alertify.error(error);
                        $scope.env.submit = false;
                    }
                )
            } else {
                userFactory.createAdministrator($scope.env.user).then(function (user) {
                        $scope.cancel();
                        alertify.success($filter('translate')('Account was created'));
                        $scope.env.users.push(user);
                        $scope.env.submit = false;
                    }, function (error) {
                        alertify.error(error);
                        $scope.env.submit = false;
                    }
                )
            }


        };

        $scope.cancel = function () {
            $scope.env.user = null;
        }
    }

})();
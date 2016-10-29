(function () {
    'use strict';
    angular.module('privateApp').controller('loginController', loginController);

    loginController.$inject = ['$scope', 'userFactory'];

    function loginController($scope, userFactory) {
        $scope.logout = function () {
            userFactory.logout();
            window.location.href = '/'
        }
    }
})();


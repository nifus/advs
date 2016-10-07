(function () {
    'use strict';
    angular.module('privateApp').controller('helpController', helpController);

    helpController.$inject = ['$scope'];

    function helpController($scope) {
        $scope.user = null;
        $scope.env = {
            loading : false
        };



        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;


            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);





    }
})();


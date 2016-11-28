(function () {
    'use strict';
    angular.module('privateApp').controller('helpController', helpController);

    helpController.$inject = ['$scope', '$filter', '$window'];

    function helpController($scope, $filter, $window) {
        $scope.user = null;
        $scope.env = {
            loading: false
        };


        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;
            $window.document.title = $filter('translate')('Help');
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


    }
})();


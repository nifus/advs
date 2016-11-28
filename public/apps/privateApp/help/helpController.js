(function () {
    'use strict';
    angular.module('privateApp').controller('helpController', helpController);

    helpController.$inject = ['$scope', '$filter', '$window'];

    function helpController($scope, $filter, $window) {
        $scope.env = {
            loading: false
        };


        function initPage(deferred) {
            $window.document.title = $filter('translate')('Help');
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


    }
})();


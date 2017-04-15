(function (angular) {
    'use strict';

    function breadcrumbDirective($window) {
        return {
            replace: true,
            restrict: 'E',
            controller: breadcrumbController,
            templateUrl: 'apps/privateApp/directives/breadcrumb/breadcrumb.html'
        };

        function breadcrumbController($scope) {
            $scope.header = $window.document;
        }
    }

    angular.module('privateApp').directive('breadcrumb', ['$window',breadcrumbDirective]);


})(window.angular);

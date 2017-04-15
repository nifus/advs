(function (angular) {
    'use strict';

    function leftMenuDirective() {
        return {
            replace: true,
            restrict: 'E',
            controller: leftController,
            templateUrl: 'apps/privateApp/directives/leftMenu/leftMenu.html'
        };

        function leftController($scope) {

        }
    }

    angular.module('privateApp').directive('leftMenu', leftMenuDirective);


})(window.angular);

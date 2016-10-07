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
            function initPage(deferred) {
                $scope.user = $scope.$parent.env.user;
                return deferred.promise;
            }

            $scope.$parent.init.push(initPage);
        }
    }

    angular.module('privateApp').directive('leftMenu', leftMenuDirective);


})(window.angular);

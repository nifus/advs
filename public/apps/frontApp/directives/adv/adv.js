(function (angular) {
    'use strict';

    function advDirective() {
        return {
            restrict: 'E',
            replace: true,
            controller: advController,
            templateUrl: '/apps/frontApp/directives/adv/adv.html',
            scope:{
                data:'='
            }
        };

        function advController($scope) {



        }


    }

    angular.module('frontApp').directive('adv', advDirective);


})(window.angular);

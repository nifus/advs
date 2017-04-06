(function (angular) {
    'use strict';


    function advPaymentDirective(advFactory, advPaymentFactory) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            controller: advPaymentController,
            templateUrl: '/apps/core/directives/advPayment/advPayment.html',
            scope: {
                model: '=',
                user: '='
            }
        };


        function advPaymentController($scope) {


          //  promises.push(prices_promise);

            $scope.env = {};



            $scope.env.guid = advFactory.guid($scope.model.id);


        }


    }

    angular.module('core').directive('advPayment', ['advFactory', 'advPaymentFactory', advPaymentDirective]);


})(window.angular);

(function (angular) {
    'use strict';


    function advPaymentDirective(tariffFactory, advPaymentFactory) {
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

            $scope.env = {


            };





        }


    }

    angular.module('core').directive('advPayment', ['tariffFactory', 'advPaymentFactory', advPaymentDirective]);


})(window.angular);

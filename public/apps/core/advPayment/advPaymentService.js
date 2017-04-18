(function (angular) {
    'use strict';
    angular.module('core').service('advPaymentService', ['$http','$q', advPaymentService]);

    function advPaymentService($http,$q) {
        return function (data) {
            var Object = data;
            Object.waiting = false;




            return Object;
        };


    }
})(angular);


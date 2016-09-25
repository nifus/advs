(function (angular, window) {
    'use strict';
    angular.module('core').service('advService', advService);
    advService.$inject = ['$http'];

    function advService( $http) {
        return function (data) {
            var Object = data;
            Object.waiting = false;




            return (Object);
        };


    }
})(angular, window);


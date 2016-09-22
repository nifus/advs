(function (angular, window) {
    'use strict';
    angular.module('core').service('newsService', newsService);
    newsService.$inject = ['$http'];

    function newsService( $http) {
        return function (data) {
            var Object = data;
            Object.waiting = false;




            return (Object);
        };


    }
})(angular, window);


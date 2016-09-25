(function (angular, window) {
    'use strict';
    angular.module('core').service('advService', advService);
    advService.$inject = ['$http'];

    function advService( $http) {
        return function (data) {
            var Object = data;
            Object.waiting = false;
            Object.CreateDate = moment(data.created_at).format('DD.MM.Y');
            Object.EndDate = moment(data.created_at).format('DD.MM.Y');
            Object.DeleteDate = moment(data.created_at).format('DD.MM.Y');




            return (Object);
        };


    }
})(angular, window);


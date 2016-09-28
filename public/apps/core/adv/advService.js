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

            Object.deleteFromWatchList = function() {
                Object.waiting = true;
                return $http.delete( '/api/user/watch-advs/'+Object.id).then(function (response) {
                    Object.waiting = false;
                    return response.data;
                })
            };

            return (Object);
        };


    }
})(angular, window);


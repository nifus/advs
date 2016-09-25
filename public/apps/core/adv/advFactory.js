(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('advFactory', advFactory);
    advFactory.$inject = ['advService', '$http'];

    function advFactory(advService, $http) {

        return {
            getByUser: getByUser

        };

        function getByUser() {
            return $http.put( '/api/user/advs').then(function (response) {
                var objs = [];
                for( var i in response.data ){
                    objs.push( new advService(response.data[i]) )
                }
                return objs;
            })
        }



    }


})(angular, window);




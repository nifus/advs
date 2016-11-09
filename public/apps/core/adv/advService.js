(function (angular, window) {
    'use strict';
    angular.module('core').service('advService', advService);
    advService.$inject = ['$http','$q'];

    function advService( $http,$q) {
        return function (data) {
            var Object = data;
            Object.waiting = false;
            Object.CreateDate = moment(data.created_at).format('DD.MM.Y');
            Object.EndDate = moment(data.created_at).format('DD.MM.Y');
            Object.DeleteDate = moment(data.created_at).format('DD.MM.Y');
            Object.MainPhoto = getMainPhoto(data.photos);

            Object.deleteFromWatchList = function() {
                Object.waiting = true;
                return $http.delete( '/api/user/watch-advs/'+Object.id).then(function (response) {
                    Object.waiting = false;
                    return response.data;
                })
            };

            Object.delete = function() {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.delete( '/api/user/advs/'+Object.id).then(function (response) {
                    Object.waiting = false;
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };

            Object.disable = function() {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.put( '/api/user/advs/'+Object.id+'/status',{status:'disabled'}).then(function (response) {
                    Object.waiting = false;
                    Object.status = 'disabled';
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };

            Object.disable = function() {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.put( '/api/user/advs/'+Object.id+'/status',{status:'active'}).then(function (response) {
                    Object.waiting = false;
                    Object.status = 'active';
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };



            return (Object);
        };
        function getMainPhoto(photos){
            return photos[0];
        }

    }
})(angular, window);


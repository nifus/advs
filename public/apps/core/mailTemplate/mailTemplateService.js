(function (angular, window) {
    'use strict';
    angular.module('core').service('mailTemplateService', ['$http', '$q', mailTemplateService]);

    function mailTemplateService($http, $q) {
        return function (data) {
            var Object = data;
            Object.waiting = false;

            Object.update = function () {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/mail/templates/' + Object.id, Object).then(function (response) {
                    Object.waiting = false;
                    for( var i in response.data){
                        Object[i] = response.data[i]
                    }
                    deferred.resolve(response.data);
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };


            return (Object);
        };


    }
})(angular, window);


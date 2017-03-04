(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('configFactory', configFactory);
    configFactory.$inject = [ '$http','$q'];

    function configFactory($http, $q) {

        return {
            get:get,
            saveAnnouncement:saveAnnouncement,
            saveInstruction:saveInstruction,
            saveFaq:saveFaq,
            //getCurrencyList:getCurrencyList
        };

        function get() {
            var defer = $q.defer();
            $http.get('/config/config.json').then(function (response) {
                defer.resolve(response.data)
            },function (response) {
                defer.reject({error:'error'})
            });
            return defer.promise;
        }

        function saveAnnouncement(type, data) {
            var defer = $q.defer();
            $http.post('/api/back/config/announcement/'+type, data).then(function (response) {
                defer.resolve(response.data)
            },function (response) {
                var error = response.data.error ? response.data.error : response.statusText
                defer.reject({error:error})
            });
            return defer.promise;
        }

        function saveInstruction(data) {
            var defer = $q.defer();
            $http.post('/api/back/config/instruction', data).then(function (response) {
                defer.resolve(response.data)
            },function (response) {
                defer.reject({error:'error'})
            });
            return defer.promise;
        }

        function saveFaq(data) {
            var defer = $q.defer();
            $http.post('/api/back/config/faq', data).then(function (response) {
                defer.resolve(response.data)
            },function (response) {
                defer.reject({error:'error'})
            });
            return defer.promise;
        }

    }


})(angular, window);




(function (angular, window) {
    'use strict';
    angular.module('core').service('faqService', faqService);
    faqService.$inject = ['$http','$q'];

    function faqService($http,$q) {
        return function (data) {
            var Object = data;
            Object.waiting = false;


            Object.delete = function () {
                return $http.delete('/api/back/faqs/' + Object.id).then(function (response) {
                    return response.data;
                })
            };

            Object.updateFaq = function (title, desc) {
                var defer = $q.defer();
                $http.post('/api/back/faqs/' + Object.id, {type: 'faq', title: title, desc: desc}).then(
                    function (response) {
                        defer.resolve();
                    }, function (response) {
                        var error = response.data.error ? response.data.error : response.statusText;
                        defer.reject({error: error})
                    }
                );
                return defer.promise;
            };

            Object.updateInstruction = function (title, desc) {
                var defer = $q.defer();
                 $http.post('/api/back/faqs/' + Object.id, {type: 'instruction', title: title, desc: desc}).then(
                    function (response) {
                        defer.resolve();
                    }, function (response) {
                        var error = response.data.error ? response.data.error : response.statusText;
                        defer.reject({error: error})
                    }
                );
                return defer.promise;
            };

            return Object;
        };


    }
})(angular, window);


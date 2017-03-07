(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('faqFactory', ['faqService', '$http', '$q',faqFactory]);

    function faqFactory(faqService, $http, $q) {

        return {
            getAll: getAll,
            storeInstruction: storeInstruction,
            storeFaq: storeFaq,
            // createGroup: createGroup
        };


        function storeInstruction(data) {
            var defer = $q.defer();

            data['type'] = 'instruction';
            $http.post('/api/faqs', data).then(
                function (response) {
                    defer.resolve(new faqService(response.data));
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

        function storeFaq(data) {
            var defer = $q.defer();

            data['type'] = 'faq';
            $http.post('/api/faqs', data).then(
                function (response) {
                    defer.resolve(new faqService(response.data));
                }, function (response) {
                    var error = response.data.error ? response.data.error : response.statusText;
                    defer.reject({error: error})
                }
            );
            return defer.promise;
        }

        function getAll() {
            return $http.get('/api/faqs').then(
                function (response) {
                    var result = [];
                    for (var i in response.data) {
                        result.push(new faqService(response.data[i]))
                    }
                    return result;
                }
            )
        }


    }


})(angular, window);




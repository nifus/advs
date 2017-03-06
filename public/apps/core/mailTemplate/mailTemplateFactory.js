(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('mailTemplateFactory', ['mailTemplateService', '$http','$q', mailTemplateFactory]);

    function mailTemplateFactory(mailTemplateService, $http,$q) {

        return {
            getAll: getAll
        };

        function getAll() {
            var defer = $q.defer();
            $http.get( '/api/mail/templates').then(function (response) {
                var templates = [];
                for( var i in response.data ){
                    templates.push( new mailTemplateService(response.data[i]) )
                }
                defer.resolve(templates);
            });
            return defer.promise;
        }



    }


})(angular, window);




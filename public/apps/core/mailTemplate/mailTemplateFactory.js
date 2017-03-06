(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('mailTemplateFactory', mailTemplateFactory);
    mailTemplateFactory.$inject = ['mailTemplateService', '$http','$q'];

    function mailTemplateFactory(mailTemplateService, $http,$q) {

        return {
            getAll: getAll
        };

        function getAll() {
            var defer = $q.defer();
            $http.get( '/api/back/mail/templates').then(function (response) {
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




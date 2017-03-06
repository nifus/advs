(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('mailTemplatesController', mailTemplatesController);

    mailTemplatesController.$inject = ['$scope', 'mailTemplateFactory', '$q', '$state', '$filter'];

    function mailTemplatesController($scope, mailTemplateFactory, $q, $state, $filter) {
        $scope.env = {
            templates:[],
            template: null,
            enable_save: 0
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if ( !$scope.user.hasPermission('mailing')) {
                $state.go('mailing');
                return;
            }

            var config_promise = mailTemplateFactory.getAll().then(function (response) {
                $scope.env.templates = response
            });
            $q.all([config_promise]).then(function () {
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);

        $scope.displayTemplate = function (template) {
            $scope.env.template = template
        };
        $scope.saveTemplate = function () {
            $scope.env.template.update().then(function (response) {
                $scope.env.template = null;
                $scope.env.enable_save = 0;
                alertify.success($filter('translate')('Template was updated'))
            })

        }
    }

})();
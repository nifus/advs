(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('mailTemplatesController', ['$scope', 'mailTemplateFactory', '$q', '$state', '$filter', mailTemplatesController]);


    function mailTemplatesController($scope, mailTemplateFactory, $q, $state, $filter) {
        $scope.env = {
            templates:[],
            template: null,
            loading: true
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if ( !$scope.user.hasPermission('mailing')) {
                $state.go('sign_in');
                return;
            }

            var config_promise = mailTemplateFactory.getAll().then(function (response) {
                $scope.env.templates = response
            });
            $q.all([config_promise]).then(function () {
                deferred.resolve();
                $scope.env.loading = false
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

        $scope.displayTemplate = function (template) {
            $scope.env.template = template
        };
        $scope.saveTemplate = function () {
            alertify.confirm( $filter('translate')("Do you really want change selected mail template?"), function (e) {
                if (e) {
                    $scope.env.template.update().then(function (response) {
                        $scope.env.template = null;
                        alertify.success($filter('translate')('Template was updated'))
                    })
                }
            });
        };
        $scope.previewTemplate = function () {

        }
    }

})();
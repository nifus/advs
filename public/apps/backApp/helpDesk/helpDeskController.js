(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('helpDeskController', helpDeskController);

    helpDeskController.$inject = ['$scope', 'configFactory', '$q', '$filter'];

    function helpDeskController($scope, configFactory, $q, $filter) {
        $scope.env  = {
            display_instruction_form: false,
            display_faq_form: false
        };
        $scope.instructions = [];
        $scope.faqs = [];
        $scope.model = {};
        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            var config_promise = configFactory.get().then(function (response) {
                if (response.instructions) {
                    $scope.instructions = response.instructions
                }
                if (response.faqs) {
                    $scope.faqs = response.faqs
                }

            });
            $q.all([config_promise]).then(function () {
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);


        $scope.saveAnnouncement = function (type) {
            configFactory.saveAnnouncement(type, $scope.model[type]).then(function (response) {
                alertify.success($filter('translate')('Announcement updated'));
            })
        };


        $scope.addInstruction = function () {
            $scope.env.display_instruction_form = true;
        };

        $scope.cancelInstruction = function () {
            $scope.env.display_instruction_form = false;
        };

        $scope.saveInstruction = function (model) {
            configFactory.saveInstruction(model).then(function () {
                alertify.success($filter('translate')('Instruction saved'));
            });
            $scope.env.display_instruction_form = false;
            $scope.model = {};
        };

        $scope.addFaq = function () {
            $scope.env.display_faq_form = true;
        };

        $scope.cancelFaq = function () {
            $scope.env.display_faq_form = false;
        }
    }

})();
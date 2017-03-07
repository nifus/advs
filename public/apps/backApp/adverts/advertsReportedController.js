(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsReportedController', advertsReportedController);

    advertsReportedController.$inject = ['$scope', 'faqFactory', '$q', '$filter'];

    function advertsReportedController($scope, faqFactory, $q, $filter) {
        $scope.env  = {
            display_instruction_form: false,
            display_faq_form: false
        };
        $scope.instructions = [];
        $scope.faqs = [];
        $scope.model_instruction = {};
        $scope.model_faq = {};
        $scope.selected = {
            instructions:[],
            faqs:[]
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;
            if ( !$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }
            var config_promise = faqFactory.getAll().then(function (response) {
                angular.forEach(response, function (element) {
                    if (element.type==='faq'){
                        $scope.faqs.push(element)
                    }else{
                        $scope.instructions.push(element)
                    }
                });
            });
            $q.all([config_promise]).then(function () {
                deferred.resolve();

            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);


        $scope.saveAnnouncement = function (type) {
            configFactory.saveAnnouncement(type, $scope.model[type]).then(function (response) {
                alertify.success($filter('translate')('Instruction updated'));
            })
        };


        $scope.addInstruction = function () {
            $scope.env.display_instruction_form = true;
        };
        $scope.editInstruction = function (instruction) {
            $scope.model_instruction = instruction;
            $scope.env.display_instruction_form = true;
        };

        $scope.cancelInstruction = function () {
            $scope.env.display_instruction_form = false;
            $scope.model_instruction = {}
        };

        $scope.saveInstruction = function (model) {
            if (model.id ){
                model.updateInstruction(model.title, model.desc).then(function () {
                    alertify.success($filter('translate')('Instruction updated'));
                },function (response) {
                    alertify.error(response.error);
                });
            }else{
                faqFactory.storeInstruction(model).then(function (response) {
                    $scope.instructions.push(response)
                    alertify.success($filter('translate')('Instruction saved'));
                },function (response) {
                    alertify.error(response.error);
                });
            }

            $scope.env.display_instruction_form = false;
            $scope.model_instruction = {};
        };

        $scope.deleteSelectedInstructions = function () {
            for( var i in $scope.selected.instructions){
                $scope.selected.instructions[i].delete();
                for( var j in $scope.instructions ){
                    if ($scope.instructions[j].id==$scope.selected.instructions[i].id ){
                        $scope.instructions.splice(j,1);
                    }
                }
            }
            $scope.selected.instructions = [];
            alertify.success($filter('translate')('Instructions deleted'));

        };



        $scope.addFaq = function () {
            $scope.env.display_faq_form = true;
        };
        $scope.editFaq = function (faq) {
            $scope.model_faq = faq;
            $scope.env.display_faq_form = true;
        };

        $scope.cancelFaq = function () {
            $scope.env.display_faq_form = false;
            $scope.model_faq = {}
        };

        $scope.saveFaq = function (model) {
            if (model.id ){
                model.updateFaq(model.title, model.desc).then(function () {
                    alertify.success($filter('translate')('Faq updated'));
                },function (response) {
                    alertify.error(response.error);
                });
            }else{
                faqFactory.storeFaq(model).then(function (response) {
                    $scope.faqs.push(response);
                    alertify.success($filter('translate')('Faq saved'));
                },function (response) {
                    alertify.error(response.error);
                });
            }

            $scope.env.display_faq_form = false;
            $scope.model_faq = {};
        };

        $scope.deleteSelectedFaqs = function () {
            for( var i in $scope.selected.faqs){
                $scope.selected.faqs[i].delete();
                for( var j in $scope.faqs ){
                    if ($scope.faqs[j].id==$scope.selected.faqs[i].id ){
                        $scope.faqs.splice(j,1);
                    }
                }
            }
            $scope.selected.faqs = [];
            alertify.success($filter('translate')('Faqs deleted'));

        }

    }

})();
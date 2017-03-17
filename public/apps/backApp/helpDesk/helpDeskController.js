(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('helpDeskController', ['$scope', 'faqFactory', '$q', '$filter',helpDeskController]);


    function helpDeskController($scope, faqFactory, $q, $filter) {
        $scope.env  = {
            display_instruction_form: false,
            display_faq_form: false,
            display_announcement_form: false,
            loading: true
        };
        $scope.instructions = [];
        $scope.faqs = [];
        $scope.announcements = [];
        $scope.model_instruction = {};
        $scope.model_faq = {};
        $scope.model_announcement = {};
        $scope.selected = {
            instructions:[],
            faqs:[],
            announcement:[]
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;
            if ( !$scope.user.hasPermission('portal')) {
                $state.go('sign_in');
                return;
            }
            var config_promise = faqFactory.getAll().then(function (response) {
                angular.forEach(response, function (element) {
                    if (element.type==='faq'){
                        $scope.faqs.push(element)
                    }else if(element.type==='instruction'){
                        $scope.instructions.push(element)
                    }else if(element.type==='announcement'){
                        $scope.announcements.push(element)
                    }
                });
            });
            $q.all([config_promise]).then(function () {
                deferred.resolve();
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);


        $scope.addAnnouncement = function () {
            $scope.env.display_announcement_form = true;
        };
        $scope.editAnnouncement = function (announcement) {
            $scope.model_announcement = announcement;
            $scope.env.display_announcement_form = true;
        };
        $scope.cancelAnnouncement = function () {
            $scope.env.display_announcement_form = false;
            $scope.model_announcement = {}
        };
        $scope.saveAnnouncement = function (model) {
            if (model.id ){
                model.updateAnnouncement(model.title, model.desc, model.announcement_type).then(function () {
                    alertify.success($filter('translate')('Announcement updated'));
                    $scope.env.display_announcement_form = false;
                    $scope.model_announcement = {};
                },function (response) {
                    alertify.error(response.error);
                });
            }else{
                faqFactory.storeAnnouncement(model).then(function (response) {
                    $scope.announcements.push(response);
                    alertify.success($filter('translate')('Announcement saved'));
                    $scope.env.display_announcement_form = false;
                    $scope.model_announcement = {};
                },function (response) {
                    alertify.error(response.error);
                });
            }


        };

        $scope.deleteSelectedAnnouncement = function () {
            for( var i in $scope.selected.announcements){
                $scope.selected.announcements[i].delete();
                for( var j in $scope.announcements ){
                    if ($scope.announcements[j].id==$scope.selected.announcements[i].id ){
                        $scope.announcements.splice(j,1);
                    }
                }
            }
            $scope.selected.announcements = [];
            alertify.success($filter('translate')('Announcements deleted'));

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
                    $scope.instructions.push(response);
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
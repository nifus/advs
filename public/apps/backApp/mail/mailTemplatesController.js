(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('mailTemplatesController', ['$scope', 'mailTemplateFactory', '$q', '$state', '$filter', mailTemplatesController]);


    function mailTemplatesController($scope, mailTemplateFactory, $q, $state, $filter) {
        $scope.env = {
            templates: [],
            template: null,
            loading: true,
            variables: [
                {
                    title: 'varAccountID',
                    desc: $filter('translate')('The ID of the account'),
                    example: 1
                },
                {
                    title: 'varAccountActivationLink',
                    desc: $filter('translate')('The generated activation link for the account'),
                    example: 'http://test.com/act/1212'
                },
                {
                    title: 'varAccountConfirmLink',
                    desc: $filter('translate')('The generated confirmation link for the account'),
                    example: 'http://test.com/act/1212'
                },
                {
                    title: 'varForgotLink',
                    desc: $filter('translate')('The generated reset link for the account'),
                    example: 'http://test.com/act/1212'
                },
                {
                    title: 'varNewPassword',
                    desc: $filter('translate')('New password'),
                    example: 'testpass'
                },
                {
                    title: 'varConfirmCode',
                    desc: $filter('translate')('Confirm code'),
                    example: '1234'
                },
                {
                    title: 'varAccountEmail',
                    desc: $filter('translate')('The login email address of the account'),
                    example: 'admin@admin.dev'
                },
                {
                    title: 'varAccountCompanyName',
                    desc: $filter('translate')('The company name of the account'),
                    example: 'Corp inc OOO'
                },
                {
                    title: 'varAccountCountry',
                    desc: $filter('translate')('The country of the account'),
                    example: 'Germany'
                },
                {
                    title: 'varAccountSubscription',
                    desc: $filter('translate')('The current subscription of the account'),
                    example: 1
                },
                {
                    title: 'varAccountPayment',
                    desc: $filter('translate')('The current preferred payment method of the account'),
                    example: 'paypal'
                },
                {
                    title: 'varContactTitle',
                    desc: $filter('translate')('The title of the contact person'),
                    example: 'Mtr'
                },
                {
                    title: 'varContactId',
                    desc: $filter('translate')('The ID of the contact person'),
                    example: '12'
                },
                {
                    title: 'varContactForename',
                    desc: $filter('translate')('The forename of the contact person'),
                    example: 'Alexander'
                },
                {
                    title: 'varContactSurname',
                    desc: $filter('translate')('The surname of the contact person'),
                    example: 'Bunzya'
                },
                {
                    title: 'varContactEmail',
                    desc: $filter('translate')('The email address of the contact person'),
                    example: 'a.bunzya@gmail.com'
                },
                {
                    title: 'varContactPhone',
                    desc: $filter('translate')('The phone of the contact person'),
                    example: "+7 345 566 44554"
                },
                {
                    title: 'varAdvertID',
                    desc: $filter('translate')('The ID of the advert'),
                    example: 1
                },
                {
                    title: 'varAdvertTitle',
                    desc: $filter('translate')('The Title of the advert'),
                    example: 'Sale Sale Sale !!!!!'
                },
                {
                    title: 'varMessageFullName',
                    desc: $filter('translate')('The full name  of the client'),
                    example: 'Alexander s Bynzya'
                },
                {
                    title: 'varMessageEmail',
                    desc: $filter('translate')('The email of the client'),
                    example: 'a.bunzya@gmail.com'
                },
                {
                    title: 'varMessagePhone',
                    desc: $filter('translate')('The phone of the client'),
                    example: '+7 12312 222'
                },
                {
                    title: 'varMessage',
                    desc: $filter('translate')('The message of the client'),
                    example: 'Hi, I want sale this house'
                },
                {
                    title: 'varMessageForAdministrator',
                    desc: $filter('translate')('The message for administrator'),
                    example: 'Hi, what this f*ck!!'
                },
                {
                    title: 'varAdvertUrl',
                    desc: $filter('translate')('The URL of the advert'),
                    example: 'http://test.com/adv/1'
                },
                {
                    title: 'varAdvertStatus',
                    desc: $filter('translate')('The current advert status'),
                    example: 'active'
                },
                {
                    title: 'varAdvertDateCreation',
                    desc: $filter('translate')('The date when this advert was created'),
                    example: '10.10.2018 12:12'
                },
                {
                    title: 'varAdvertDateDeletion',
                    desc: $filter('translate')('The date when this advert will be automatically deleted'),
                    example: '10.10.2018 12:12'
                },
                {
                    title: 'varAdvertDateDeleted',
                    desc: $filter('translate')('The date when this advert was deleted'),
                    example: '10.10.2018 12:12'
                },
                {
                    title: 'varAdvertDateEnds',
                    desc: $filter('translate')('The date when this advert will end'),
                    example: '10.10.2018 12:12'
                },
                {
                    title: 'varAdvertDateBlocked',
                    desc: $filter('translate')('The date when this advert was blocked'),
                    example: '10.10.2018 12:12'
                },
                {
                    title: 'varAdvertDateActivated',
                    desc: $filter('translate')('The date when this advert was activated'),
                    example: '10.10.2018 12:12'
                },
            ],
            display_template: false,
            template_text: null,
            using_variables: []
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if (!$scope.user.hasPermission('mailing')) {
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

            var using =[];
            var variables = template.variables;
            for( var j in $scope.env.variables){
                for( var i in variables){
                    if ( $scope.env.variables[j].title==variables[i] ){
                        using.push($scope.env.variables[j])
                    }
                }
            }

            $scope.env.template = template;
            $scope.env.using_variables = using;
        };
        $scope.saveTemplate = function () {
            alertify.confirm($filter('translate')("Do you really want change selected mail template?"), function (e) {
                if (e) {
                    $scope.env.template.update().then(function (response) {
                        $scope.env.template = null;
                        alertify.success($filter('translate')('Template was updated'))
                    })
                }
            });
        };
        $scope.previewTemplate = function (text) {

            var found = text.match(/\[([^\]]*)\]/g);

            found.forEach(function (el) {
                el = el.replace(/\[|\]/g,'');

                for ( var i in $scope.env.variables ){
                    if ($scope.env.variables[i].title==el ){
                        text = text.replace('['+$scope.env.variables[i].title+']',$scope.env.variables[i].example)
                    }
                }
            })

            $scope.env.display_template = true;
            $scope.env.template_text = text;
        }
    }

})();
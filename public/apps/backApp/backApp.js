(function (angular) {
    'use strict';
    angular.module('backApp', ['ui.router', 'satellizer', 'core', 'angular-table', 'ngCookies', 'naif.base64','gettext','ui.select2','ui.bootstrap.datetimepicker','ckeditor']).
    config(function ($stateProvider, $urlRouterProvider) {
        var refer = window.location.href;
        if ( refer.indexOf('pr64-coworking')==-1 ){
            window.server = 'http://coworking.ru/';
        }else{
            window.server = '/';
        }

        $urlRouterProvider.otherwise('/requests');

        $stateProvider.state('users', {
            url: '/users',
            templateUrl: 'apps/backApp/users/usersList.html',
            controller: 'usersListController',
            accessSection: 'users',
            accessType: 'read'
        }).state('users-create', {
            url: '/users/create',
            templateUrl: 'apps/backApp/users/usersForm.html',
            controller: 'usersFormController',
            accessSection: 'users',
            accessType: 'edit'
        }).state('users-edit', {
            url: '/users/:id',
            templateUrl: 'apps/backApp/users/usersForm.html',
            controller: 'usersFormController',
            accessSection: 'users',
            accessType: 'edit'
        }).state('logs', {
            url: '/logs',
            templateUrl: 'apps/backApp/logs/logList.html',
            controller: 'logListController',
            accessSection: 'logs',
            accessType: 'read'
        }).state('events', {
            url: '/events',
            templateUrl: 'apps/backApp/event/eventList.html',
            controller: 'eventListController',
            accessSection: 'events',
            accessType: 'read'
        }).state('events-create', {
            url: '/events-create',
            templateUrl: 'apps/backApp/event/eventForm.html',
            controller: 'eventFormController',
            accessSection: 'events',
            accessType: 'edit'
        }).state('event-edit', {
            url: '/events/:id',
            templateUrl: 'apps/backApp/event/eventForm.html',
            controller: 'eventFormController',
            accessSection: 'events',
            accessType: 'edit'
        }).state('pages', {
            url: '/pages',
            templateUrl: 'apps/backApp/page/pageList.html',
            controller: 'pageListController',
            accessSection: 'pages',
            accessType: 'read'
        }).state('pages-edit', {
            url: '/pages/:id',
            templateUrl: 'apps/backApp/page/pageForm.html',
            controller: 'pageFormController',
            accessSection: 'pages',
            accessType: 'edit'
        }).state('contacts-edit', {
            url: '/contacts',
            templateUrl: 'apps/backApp/contact/contactForm.html',
            controller: 'contactFormController',
            accessSection: 'contacts',
            accessType: 'edit'
        }).state('tariffs', {
            url: '/tariffs',
            templateUrl: 'apps/backApp/tariff/tariffList.html',
            controller: 'tariffListController',
            accessSection: 'tariff',
            accessType: 'read'
        }).state('tariffs-edit', {
            url: '/tariffs/:id',
            templateUrl: 'apps/backApp/tariff/tariffForm.html',
            controller: 'tariffFormController',
            accessSection: 'tariff',
            accessType: 'edit'
        }).state('tariff-create', {
            url: '/tariffs-create',
            templateUrl: 'apps/backApp/tariff/tariffForm.html',
            controller: 'tariffFormController',
            accessSection: 'tariff',
            accessType: 'edit'
        }).state('requests', {
            url: '/requests',
            templateUrl: 'apps/backApp/request/requestList.html',
            controller: 'requestListController',
            accessSection: 'requests',
            accessType: 'edit'
        }).state('requests-create', {
            url: '/requests-create',
            templateUrl: 'apps/backApp/request/requestForm.html',
            controller: 'requestFormController',
            accessSection: 'requests',
            accessType: 'edit'
        }).state('requests-edit', {
            url: '/requests/:id',
            templateUrl: 'apps/backApp/request/requestForm.html',
            controller: 'requestFormController',
            accessSection: 'requests',
            accessType: 'edit'
        })

    }).filter('to_trusted', ['$sce', function ($sce) {
            return function (text) {
                return $sce.trustAsHtml(text);
            };
        }])
        .
    run(['userFactory', '$state', '$rootScope', function (userFactory, $state, $rootScope) {

        $rootScope.$watch(function () {
            return $state.current
        }, function (value) {
            if (value == undefined) {
                return;
            }


            userFactory.getAuthUser().then( function(user){

                if ( !user ){
                   window.location.href='/signin'
                }

                if (value['accessSection'] != undefined ) {
                    if ( user == null || !user.hasAccess(value['accessSection'],value['accessType']) ){
                        window.history.back();
                        return;
                    }
                }
            });
        }, true);

    }]);

})(angular);



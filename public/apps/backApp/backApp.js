(function (angular) {
    'use strict';
    angular.module('backApp', ['ui.router', 'satellizer', 'core', 'naif.base64', 'ngCookies', 'gettext', 'textCounter', "checklist-model"]).config(['$stateProvider', '$urlRouterProvider', '$authProvider', function ($stateProvider, $urlRouterProvider, $authProvider) {


        $authProvider.loginUrl = '/api/user/authenticate';

        $urlRouterProvider.otherwise('/');
        $stateProvider.state('sign_in', {
            url: '/',
            templateUrl: '../apps/backApp/signIn/signIn.html',
            controller: 'signIn'
        }).state('dashboard', {
            url: '/dashboard',
            templateUrl: '../apps/backApp/dashboard/dashboard.html',
            controller: 'dashboard'
        }).state('announcement', {
            url: '/announcement',
            templateUrl: '../apps/backApp/announcement/announcement.html',
            controller: 'announcementController'
        }).state('help-desk', {
            url: '/help-desk',
            templateUrl: '../apps/backApp/helpDesk/helpDesk.html',
            controller: 'helpDeskController'
        }).state('adverts-search', {
            url: '/adverts/search',
            templateUrl: '../apps/backApp/adverts/search.html',
            controller: 'advertsSearchController'
        }).state('adverts-blocked', {
            url: '/adverts/blocked',
            templateUrl: '../apps/backApp/adverts/blocked.html',
            controller: 'advertsBlockedController'
        }).state('adverts-reported', {
            url: '/adverts/reported',
            templateUrl: '../apps/backApp/adverts/reported.html',
            controller: 'advertsReportedController'
        }).state('accounts-search', {
            url: '/accounts/search',
            templateUrl: '../apps/backApp/accounts/search.html',
            controller: 'accountsSearchController'
        }).state('accounts-created', {
            url: '/accounts/created',
            templateUrl: '../apps/backApp/accounts/created.html',
            controller: 'accountsCreatedController'
        }).state('accounts-blocked', {
            url: '/accounts/blocked',
            templateUrl: '../apps/backApp/accounts/blocked.html',
            controller: 'accountsBlockedController'
        }).state('administration', {
            url: '/administration',
            templateUrl: '../apps/backApp/administration/administration.html',
            controller: 'administrationController'
        })

    }]).run(function (gettextCatalog) {

        var lang = localStorage.getItem('lang');
        lang = lang == undefined ? 'en' : lang;
        gettextCatalog.setCurrentLanguage(lang);
        moment.locale("de")
    }).filter('to_trusted', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        };
    }])

})(angular);



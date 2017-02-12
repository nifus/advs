(function (angular) {
    'use strict';
    angular.module('backApp', ['ui.router', 'satellizer', 'core', 'naif.base64', 'ngCookies', 'gettext','textCounter']).config(['$stateProvider', '$urlRouterProvider', '$authProvider', function ($stateProvider, $urlRouterProvider, $authProvider) {


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



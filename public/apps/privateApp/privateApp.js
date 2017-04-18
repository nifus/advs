(function (angular) {
    'use strict';
    angular.module('privateApp', ['core', 'satellizer', 'ngCookies', 'ui.router', 'ui.bootstrap', 'validation.match', 'ngAutocomplete', 'gettext','ui.bootstrap.datetimepicker','checklist-model','ngFileUpload']).config(function ($stateProvider, $urlRouterProvider) {


        $urlRouterProvider.otherwise('/dashboard');

        $stateProvider.state('dashboard', {
            url: '/dashboard',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController'
        }).state('help', {
            title: 'Help',
            url: '/help',
            templateUrl: 'apps/privateApp/help/help.html',
            controller: 'helpController'
        }).state('my-adv', {
            url: '/my-adv',
            templateUrl: 'apps/privateApp/adv/my.html',
            controller: 'myController'
        }).state('my-watch-list', {
            url: '/my-watch-list',
            templateUrl: 'apps/privateApp/adv/myWatchList.html',
            controller: 'myWatchListController'
        }).state('my-settings', {
            url: '/my-settings',
            templateUrl: 'apps/privateApp/settings/settings.html',
            controller: 'settingsController'
        }).state('my-subscription', {
            url: '/my-subscription',
            templateUrl: 'apps/privateApp/subscription/subscription.html',
            controller: 'subscriptionController'
        }).state('delete-account', {
            url: '/delete-account',
            templateUrl: 'apps/privateApp/deleteAccount/deleteAccount.html',
            controller: 'deleteAccountController'
        }).state('adv-edit', {
            url: '/adv/:id/edit',
            templateUrl: 'apps/privateApp/adv/edit.html',
            controller: 'editController'
        }).state('adv-react', {
            url: '/adv/:id/edit/:react',
            templateUrl: 'apps/privateApp/adv/edit.html',
            controller: 'editController'
        }).state('profile', {
            url: '/profile',
            templateUrl: 'apps/privateApp/profile/profile.html',
            controller: 'profileController'
        })

    }).run(function (gettextCatalog) {

        var lang = localStorage.getItem('lang');
        lang = lang == undefined ? 'en' : lang;
        gettextCatalog.setCurrentLanguage(lang);
        moment.locale("de")


    }).filter('limitFromTo', function () {
        return function (input, from, to) {
            return (input != undefined) ? input.slice(from, to) : '';
        }
    }).filter('to_trusted', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        };
    }]);


})(angular);



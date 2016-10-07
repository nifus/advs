(function (angular) {
    'use strict';
    angular.module('privateApp', ['core', 'satellizer', 'ngCookies', 'ui.router', 'ui.bootstrap', 'validation.match']).config(function ($stateProvider, $urlRouterProvider) {

        /*var refer = window.location.href;
         if ( refer.indexOf('pr64-coworking')==-1 ){
         window.server = 'http://coworking.ru/';
         }else{
         window.server = '/';
         }*/

        $urlRouterProvider.otherwise('/dashboard');

        $stateProvider.state('dashboard', {
            url: '/dashboard',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController'
        }).state('help', {
            url: '/help',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController'
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
        }).state('delete-account', {
            url: '/delete-account',
            templateUrl: 'apps/privateApp/deleteAccount/deleteAccount.html',
            controller: 'deleteAccountController'
        }).state('adv', {
            url: '/adv/:id',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController'
        })

    }).filter('limitFromTo', function () {
        return function (input, from, to) {
            //console.log(input)
            return (input != undefined) ? input.slice(from, to) : '';
        }
    });


})(angular);



(function (angular) {
    'use strict';
    angular.module('privateApp', ['core','satellizer','ngCookies','ui.router']).config(function ($stateProvider, $urlRouterProvider) {

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
            controller: 'dashboardController',
        }).state('help', {
            url: '/help',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController',
        }).state('my-adv', {
            url: '/my-adv',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController',
        }).state('my-watch-list', {
            url: '/my-watch-list',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController',
        }).state('my-settings', {
            url: '/my-settings',
            templateUrl: 'apps/privateApp/dashboard/dashboard.html',
            controller: 'dashboardController',
        })

    })



})(angular);



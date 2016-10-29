(function (angular) {
    'use strict';
    angular.module('frontApp', ['core','vcRecaptcha','ngCookies','satellizer','ngAutocomplete','checklist-model','ui.bootstrap.datetimepicker','naif.base64','AngularGM','pascalprecht.translate'], function ($interpolateProvider) {
       // $interpolateProvider.startSymbol('%');
        //$interpolateProvider.endSymbol('%');
    }).
    config(function ( $authProvider, $translateProvider) {
        // $authProvider.httpInterceptor = false;
        $authProvider.loginUrl = '/api/user/authenticate';


        var lang = localStorage.getItem('lang');
        lang = lang==undefined ? 'en' : lang;
        console.log(lang)
        $translateProvider.preferredLanguage(lang);

    });

        var compareTo = function() {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function(scope, element, attributes, ngModel) {

                ngModel.$validators.compareTo = function(modelValue) {
                    return modelValue == scope.otherModelValue;
                };

                scope.$watch("otherModelValue", function() {
                    ngModel.$validate();
                });
            }
        };
    };

    angular.module("frontApp").directive("compareTo", compareTo);



})(angular);



(function (angular) {
    'use strict';
    angular.module('frontApp', ['core', 'vcRecaptcha', 'ngCookies', 'satellizer', 'ngAutocomplete', 'checklist-model', 'ui.bootstrap.datetimepicker', 'naif.base64', 'AngularGM', 'gettext'], function ($interpolateProvider) {
        // $interpolateProvider.startSymbol('%');
        //$interpolateProvider.endSymbol('%');
    }).config(function ($authProvider) {
        // $authProvider.httpInterceptor = false;
        $authProvider.loginUrl = '/api/user/authenticate';


        // $translateProvider.preferredLanguage(lang);

    }).run(function (gettextCatalog) {

        var lang = localStorage.getItem('lang');
        lang = lang == undefined ? 'en' : lang;
        gettextCatalog.setCurrentLanguage(lang);
    });

    var compareTo = function () {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function (scope, element, attributes, ngModel) {

                ngModel.$validators.compareTo = function (modelValue) {
                    return modelValue == scope.otherModelValue;
                };

                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
    };

    angular.module("frontApp").directive("compareTo", compareTo);


})(angular);



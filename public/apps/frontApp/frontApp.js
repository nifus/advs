(function (angular) {
  'use strict';
  angular.module('frontApp', ['ui.router', 'core', 'vcRecaptcha', 'ngCookies', 'satellizer', 'ngAutocomplete', 'checklist-model', 'ui.bootstrap.datetimepicker', 'ngFileUpload', 'AngularGM', 'gettext', 'angucomplete-alt', 'disableAll', 'textCounter', 'ui.bootstrap', 'ngAnimate'], function () {

  }).config(['$authProvider', '$stateProvider', function ($authProvider, $stateProvider) {
    $authProvider.loginUrl = '/api/user/authenticate';
    //$locationProvider.html5Mode(true);

    $stateProvider
        .state('searchForm', {
          url: '/',
          templateUrl: 'apps/frontApp/adv/search/form.html',
          controller: 'formSearchController'
        })
        .state('searchFormRestore', {
          url: '/search/:id',
          templateUrl: 'apps/frontApp/adv/search/form.html',
          controller: 'formSearchController'
        })
        .state('searchResult', {
          url: '/search/:id/result',
          templateUrl: 'apps/frontApp/adv/searchResult/listing/index.html',
          controller: 'resultSearchController'
        })
        .state('searchResult.paginate', {
          url: '/search/:id/result/page/:page',
          templateUrl: 'apps/frontApp/adv/searchResult/listing/index.html',
          controller: 'resultSearchController'
        })
        .state('searchResult.ViewAdvert', {
          url: '/search/:id/result//view/:advert',
          templateUrl: 'apps/frontApp/adv/searchResult/view/index.html',
          controller: 'resultSearchController'
        })
    /*,
     {
     name: 'result',
     url: '/search=:id/result',
     templateUrl: 'apps/frontApp/adv/search/result.html',
     controller: 'searchResultController'
     },
     {
     name: 'result.paginate',
     url: '/search=:id/result/page=:page',
     templateUrl: 'apps/frontApp/adv/search/result.html',
     controller: 'searchResultController'
     },
     {
     name: 'result.view',
     url: '/search=:id/result/view=:advert_id',
     templateUrl: 'apps/frontApp/adv/search/result.html',
     controller: 'searchResultController'
     }*/

  }]).run(function (gettextCatalog) {
    var lang = localStorage.getItem('lang');
    lang = lang == undefined ? 'en' : lang;
    gettextCatalog.setCurrentLanguage(lang);
    moment.locale("de")
  }).filter('to_trusted', ['$sce', function ($sce) {
    return function (text) {
      return $sce.trustAsHtml(text);
    };
  }]);

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

  Object.defineProperty(Array.prototype, 'chunk_inefficient', {
    value: function (chunkSize) {
      var array = this;
      return [].concat.apply([],
          array.map(function (elem, i) {
            return i % chunkSize ? [] : [array.slice(i, i + chunkSize)];
          })
      );
    }
  });
})(angular);



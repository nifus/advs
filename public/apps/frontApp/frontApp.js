(function (angular) {
  'use strict';
  angular.module('frontApp', ['ui.router', 'core', 'vcRecaptcha', 'ngCookies', 'satellizer', 'ngAutocomplete', 'checklist-model', 'ui.bootstrap.datetimepicker', 'ngFileUpload', 'AngularGM', 'gettext', 'angucomplete-alt', 'disableAll', 'textCounter', 'ui.bootstrap', 'ngAnimate'], function () {

  }).config(['$authProvider', '$stateProvider','$locationProvider', function ($authProvider, $stateProvider, $locationProvider) {
    $authProvider.loginUrl = '/api/user/authenticate';
    $locationProvider.html5Mode({ enabled: true, requireBase: true });
    //$locationProvider.hashPrefix('!');
    $stateProvider
    .state('index', {
      url: '/',
      templateUrl: 'apps/frontApp/index/index.html',
      controller: 'indexController'
    })
    .state('contacts', {
      url: '/contacts',
      templateUrl: 'apps/frontApp/contacts/contacts.html',
      controller: 'contactsController'
    }).state('agb', {
      url: '/agb',
      templateUrl: 'apps/frontApp/agb/agb.html',
      controller: 'agbController'
    }).state('impressum', {
      url: '/impressum',
      templateUrl: 'apps/frontApp/impressum/impressum.html',
      controller: 'impressumController'
    })
    .state('searchForm', {
      url: '/search',
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
      templateUrl: 'apps/frontApp/adv/search/result.html',
      controller: 'resultSearchController'
    })
    .state('searchResult.paginate', {
      url: '/search/:id/result/page/:page',
      templateUrl: 'apps/frontApp/adv/search/result.html',
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

    //$locationProvider.html5Mode(true);

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



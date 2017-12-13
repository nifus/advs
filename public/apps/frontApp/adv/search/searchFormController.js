(function () {
  'use strict';
  angular.module('frontApp').controller('searchFormController', searchFormController);

  searchFormController.$inject = ['$scope'];

  function searchFormController($scope) {

    $scope.base = {
      display:{
        action: 'form'
      },
      search_id: null,
      advert_id: null
    };



    $scope.displaySearchResults = function (id) {
      $scope.base.search_id = id;
      $scope.base.display.action = 'result-listing';
      window.location.hash = 'result='+id
    }

    $scope.$watch(function () {return window.location.hash}, function (value) {
      updateRoute(value)
    });

    function updateRoute(value) {
      if (value.indexOf('form=') !== -1) {
        $scope.base.search_id = value.match(/form=([0-9]*)/)[1];
        $scope.base.display.action = 'form';
      }else if (value.indexOf('result=') !== -1) {
        $scope.base.search_id = value.match(/result=([0-9]*)/)[1];
        $scope.base.display.action = 'result-listing';
      }else if (value.indexOf('advert=') !== -1) {
        $scope.base.advert_id = value.match(/advert=([0-9]*)/)[1];
        $scope.base.display.action = 'result-view';
      }else{
        $scope.base.display.action = 'form';
        $scope.base.search_id = null
      }
    }
    var hash = window.location.hash;
    updateRoute(hash)
  }
})();


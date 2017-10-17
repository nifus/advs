(function (angular) {
  'use strict';

  function cityDetectDirective($http) {
    return {
      restrict: 'E',
      //link: citySelectLink,
      controller: cityDetectController,
      templateUrl: '/apps/frontApp/directives/cityDetect/cityDetect.html',
      scope: {
        city: '@'
      }
    };





    function cityDetectController($scope) {
      $scope.displayCity = $scope.city=='' ? 'Your city' : $scope.city

      $scope.popover = false
      $scope.options =  {
        types: '(cities)',
        country: 'de'
      };

      $scope.address = {
        value: null,
        details: null
      };

      $scope.openPopover = function () {
        $scope.popover = true
        window.setTimeout(function () {
          $('div.city-detect input').focus()
        }, 200)
      }

      $scope.closePopover = function () {
        $scope.popover = false
      }

      $scope.$watch('address.details', function (value) {
        if (value && value.place_id){
          var detect = {
            'city' : value.name,
            'place_id' : value.place_id,
            'iso' : value.address_components[value.address_components.length-1].short_name,
            'country' : value.address_components[value.address_components.length-1].long_name,
            'coordinates' : {
              lat: value.geometry.location.lat(),
              lng: value.geometry.location.lng()
            }
          }
          $scope.changeCity(detect)
        }
      }, true)

      $scope.changeCity = function (value) {
        $http.post('/api/change-city',value).then(function (response) {
          window.location.reload(true)
        })
      }
    }
  }

  angular.module('frontApp').directive('cityDetect', ['$http',cityDetectDirective]);


})(window.angular);

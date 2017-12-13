(function () {
  'use strict';
  angular.module('frontApp').controller('searchAdvController', searchAdvController);

  searchAdvController.$inject = ['$scope', 'advFactory', '$http', '$q', '$filter', 'searchLogFactory','$timeout'];

  function searchAdvController($scope, advFactory, $http, $q, $filter, searchLogFactory, $timeout) {

    $scope.env = {
      detail_search: false,
      loading: true,
      submit: false,
      address: {},
      id: null,
      promises: [],
      heating_systems: [
        $filter('translate')('Any'),
        $filter('translate')('Self-contained heating'),
        $filter('translate')('Central heating'),
        $filter('translate')('Teleheating'),
      ],
      display_city_field: true
    };
    if (window.location.hash.indexOf('id=') !== -1) {
      $scope.env.id = window.location.hash.match(/id=([0-9]*)/)[1];
    }

    var dataSetPromise = advFactory.getDataSets().then(function (response) {
      $scope.env.categories = response.categories;
      $scope.env.subcats = response.sub_categories;
      $scope.env.equipments = response.equipments;
    });
    $scope.env.promises.push(dataSetPromise);

    if ($scope.env.id != null) {
      $scope.env.display_city_field = false;
      var searchPromise = $http.get('/api/search/' + $scope.env.id).then(function (response) {
        if (response.data.success == true) {
          $scope.search = response.data.search.query;
          $scope.env.address = {
            address: response.data.search.query.address,
            lat: response.data.search.query.lat,
            lng: response.data.search.query.lng,
            radius: response.data.search.query.radius,
          };
          $scope.env.place = response.data.place;
        }
      });
      $scope.env.promises.push(searchPromise);
    }
    $q.all($scope.env.promises).then(function () {
      $scope.env.loading = false;
    }, function () {
      $scope.env.loading = false;
    });

    $scope.search = {
      category: 1,
      type: 'rent',
      subcategory: [],
      equipments: [],
      parking_place: 'Any',
      heating: 'Any',
      pets: 'Any',
      radius: "1",
      air_conditioner: "Any",
      edp_cabling: "Any",
      development: "Any",
      building_permission: "Any"
    };


    $scope.detailSearch = function (flag) {
      $scope.env.detail_search=flag
      if (flag){
        $timeout(function () {
          $('#focus').focus()
        },100)

      }
    }
    $scope.changeSubCategory = function (category_id) {
      var index = $scope.search.subcategory.indexOf(category_id);
      if (index==-1){
        $scope.search.subcategory.push(category_id)
      }else{
        $scope.search.subcategory.splice(index,1)
      }
    }
    $scope.changeEquipment = function (eq_id) {
      var index = $scope.search.equipments.indexOf(eq_id);
      if (index==-1){
        $scope.search.equipments.push(eq_id)
      }else{
        $scope.search.equipments.splice(index,1)
      }
    }

    $scope.searchAdvs = function (data) {
      $scope.env.submit = true;
      searchLogFactory.storeAdvs(data).then(function (response) {
        window.location.href = '/search/' + response.id;
      }, function () {
        //exception
      });

    }


    $scope.$watch('search', function (value) {
      console.log(value)
    }, true);


    $scope.clearCityField = function () {
      $scope.search.city_id = undefined;
      $scope.env.display_city_field = true;
      $scope.env.address = null;
    }


    $scope.setCategory = function (category) {
      $scope.search.category = category;
    };

    $scope.setType = function (type) {
      $scope.search.type = type;
    };
  }
})();


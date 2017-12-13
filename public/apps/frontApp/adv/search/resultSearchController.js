(function () {
  'use strict';
  angular.module('frontApp').controller('resultSearchController', resultSearchController);

  resultSearchController.$inject = ['$scope', 'searchLogFactory', '$q', '$interval', '$filter', 'advFactory', '$cookies', '$state'];

  function resultSearchController($scope, searchLogFactory, $q, $interval, $filter, advFactory, $cookies, $state) {
    $scope.adv = null;
    $scope.message = {};
    $scope.env = {
      adv_id: $state.params.advert,
      search_id: $state.params.id,
      search: null,
      loading: true,
      per_page: 20,
      sortby: 'date_create',
      page: (!$state.params.page ? 1 : $state.params.page),
      pages: null,
      current: [],
      display_map: false,
      display_view_map: false,
      map: null,
      submit: false,
      rows: null,
      display_additional_fields: false
    };

    $scope.promises = [];

    $scope.displayAdditionalFields = function (flag) {
      $scope.env.display_additional_fields = !flag
    };

    $scope.changePerPage = function (value, page, need_update) {
      $scope.env.per_page = value * 1;
      if (page == undefined) {
        $scope.env.page = value * 1;
        $scope.setPage(1);
      }
      if (need_update != undefined && need_update == true) {
        updateSearch();
      }
    };

    $scope.range = function (min, max, step) {
      step = step || 1;
      var input = [];
      for (var i = min; i <= max; i += step) {
        input.push(i);
      }
      return input;
    };

    $scope.setPage = function (page) {
      if ($scope.env.page == 1 && page == 1) {
        //$state.go('searchResult.paginate',{id: $scope.env.search_id, page: 1})
      } else {
        $state.go('searchResult.paginate',{id: $scope.env.search_id, page: page})
      }
      $scope.env.page = page;
      updatePagination();
    };

    $scope.displayMap = function (flag, need_update) {
      $scope.env.display_map = flag;
      $scope.env.display_view_map = flag;
      if (need_update) {
        updateSearch();
      }
      if ($scope.env.display_map === true) {
        if ($scope.env.adv_id) {
          initGoogleMapsView();
        } else {
          initGoogleMapsListing();
        }

      }
    };

    $scope.changeSort = function (value, need_update) {
      $scope.env.sortby = value;
      if (value == 'date_create') {
        $scope.env.rows = $filter('orderBy')($scope.env.rows, '-created_at');
      } else if (value == 'price_up') {
        $scope.env.rows = $filter('orderBy')($scope.env.rows, '-price', true);
      } else if (value == 'price_down') {
        $scope.env.rows = $filter('orderBy')($scope.env.rows, '-price');
      }
      updatePagination();

      if (need_update != undefined && need_update == true) {
        updateSearch();
      }
    };

    $scope.addToFav = function (adv, flag) {
      if (flag === true) {
        adv.addToFavList($scope.user);
        alertify.success($filter('translate')('Adv added to watchlist'));
      } else {
        adv.deleteFromFavList($scope.user);
        alertify.success($filter('translate')('Adv removed from watchlist'));
      }
    }

    function updatePagination() {
      if ($scope.env.rows == null) {
        return;
      }
      var pages = Math.round($scope.env.rows.length / $scope.env.per_page);
      $scope.env.pages = pages > 1 ? pages : null;

      var rows = $scope.env.rows.slice(($scope.env.page - 1) * $scope.env.per_page, (($scope.env.page - 1) * $scope.env.per_page) + $scope.env.per_page);
      //
      $scope.env.current = rows.chunk_inefficient(2);

      window.scrollTo(0, 0);
    }

    function updateSearch() {
      $scope.env.search.updateConfig(
          {
            per_page: $scope.env.per_page,
            sortby: $scope.env.sortby,
            display_map: $scope.env.display_map,
            zoom: $scope.env.zoom,
            lat: $scope.env.lat,
            lng: $scope.env.lng
          }
      )
    }


    ////
    $scope.favlist = function (flag) {
      if (flag === true) {
        $scope.adv.addToFavList($scope.user);
        alertify.success('Adv added to watchlist');
      } else {
        $scope.adv.deleteFromFavList($scope.user);
        alertify.success('Adv removed from watchlist');
      }
    };


    $scope.displayPhotos = function () {
      $scope.env.display_view_map = false;
    };


    $scope.displayAdvert = function (advert) {
      $scope.adv = advert;
        $state.go('searchResult.ViewAdvert',{id: $scope.env.search_id, advert: advert.id})
    };

    $scope.goBack = function () {
      $scope.adv = null;
      if (!$scope.env.rows) {
        $state.go('searchResult',{id: $scope.env.search_id})
       // window.location.href = '/search/' + $scope.env.search_id;
      }
    };
    $scope.backToSearchForm = function () {
      $state.go('searchFormRestore',{id: $scope.env.search_id})
    };

    $scope.newSearch = function () {
      $scope.env.submit = true;
      $scope.env.loading = true;
      $scope.env.search.updateQuery($scope.env.search.query).then(function (response) {
        $scope.env.search.getAdvertResult({}).then(function (response) {
          $scope.env.submit = false;
          $scope.env.loading = false;
          $scope.env.rows = response.advs;
          console.log(response.advs)
        });
        /*if ( response.data.success==true){
         window.location.href = '/search/'+response.data.id;
         }*/
      });
    }

    function initGoogleMapsListing() {
      var interval = $interval(function () {

        if (document.getElementById('map')) {
          if ($scope.env.zoom != null) {

            $scope.env.map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: $scope.env.lat, lng: $scope.env.lng},
              zoom: $scope.env.zoom
            });
          } else {

            $scope.env.map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: $scope.env.search.query.lat * 1, lng: $scope.env.search.query.lng * 1},
              zoom: 15
            });
          }
          $scope.env.map.addListener('zoom_changed', function () {
            $scope.env.zoom = $scope.env.map.getZoom();
            updateSearch();
          });
          $scope.env.map.addListener('center_changed', function (e, val) {
            var center = $scope.env.map.getCenter();
            $scope.env.lat = center.lat();
            $scope.env.lng = center.lng();
            updateSearch();
          });
          var markers = [];
          var bounds = new google.maps.LatLngBounds();
          var infowindow = null;
          for (var i in $scope.env.rows) {
            var marker = new google.maps.Marker({
              position: {lat: $scope.env.rows[i].lat * 1, lng: $scope.env.rows[i].lng * 1},
            });
            marker.adv = $scope.env.rows[i];
            marker.addListener('click', function (e) {
              if (infowindow != null) {
                infowindow.close()
              }
              infowindow = new google.maps.InfoWindow({
                content: getAdvContent(this.adv)
              });
              infowindow.open($scope.env.map, this);
            });
            markers.push(marker);
            bounds.extend(marker.getPosition());
          }

          if ($scope.env.zoom == null) {
            $scope.env.map.fitBounds(bounds);
          }
          $scope.env.zoom = $scope.env.map.getZoom();
          var center = $scope.env.map.getCenter();
          $scope.env.lat = center.lat();
          $scope.env.lng = center.lng();
          updateSearch();

          new MarkerClusterer($scope.env.map, markers);

          $interval.cancel(interval);
        }
      }, 1000)

    }


    //
    function initListing(id) {
      $scope.env.loading = true;
      $scope.promises = [];
      if ($scope.env.search == null) {
        var defer = $q.defer();
        searchLogFactory.getById(id).then(function (response) {
          $scope.env.search = response;
          if (response.config && response.config.zoom) {
            $scope.env.zoom = response.config.zoom;
            $scope.env.lng = response.config.lng;
            $scope.env.lat = response.config.lat;
            $scope.env.per_page = response.config.per_page;
          }
          if ($scope.env.rows == null) {
            $scope.env.search.getAdvertResult({}).then(function (response) {
              $scope.env.rows = response.rows;
              defer.resolve();
            });
            // $scope.promises.push(advPromise);
          }
        });
        $scope.promises.push(defer.promise);
      }


      $q.all($scope.promises).then(function () {

        $scope.env.loading = false;
        if ($scope.env.search.config && $scope.env.search.config.display_map) {
          $scope.displayMap($scope.env.search.config.display_map, false);
        }

        $scope.setPage($scope.env.page);

        if ($scope.env.search.config && $scope.env.search.config.per_page) {
          $scope.changePerPage($scope.env.search.config.per_page, $scope.env.page)
        }
        if ($scope.env.search.config && $scope.env.search.config.sortby) {
          $scope.changeSort($scope.env.search.config.sortby)
        }

      });
    }

    function initView() {
      $scope.env.loading = true;
      $scope.env.display_view_map = false;

      if ($scope.env.rows) {
        for (var i in $scope.env.rows) {
          if ($scope.env.rows[i].id == $scope.env.adv_id) {
            $scope.adv = $scope.env.rows[i];
            break;
          }
        }
      } else {
        var advPromise = advFactory.getById($scope.env.adv_id).then(function (response) {
          $scope.adv = response;
          console.log(response)
        });
        $scope.promises.push(advPromise);
      }


      if ($scope.env.search == null) {
        var searchPromise = searchLogFactory.getById($scope.env.search_id).then(function (response) {
          $scope.env.search = response;
        });
        $scope.promises.push(searchPromise);
        //initGoogleMaps();
      }


      $q.all($scope.promises).then(function () {
        $scope.env.loading = false;
        $('body').scrollTop();
      });
    }

    function getAdvContent(adv) {
      return '<div class="media"> ' +
          '<div class="media-left"> ' +
          '<a href="/search/' + $scope.env.result_id + '#/view' + adv.id + '"><img class="media-object" style="width:150px" src="' + adv.photos[0] + '" "></a> ' +
          '</div> ' +
          '<div class="media-body"> ' +
          '<h4 class="media-heading"><a href="/search/' + $scope.env.result_id + '#/view' + adv.id + '">' + adv.title + '</a></h4> ' +
          '</div> ' +
          '</div>';

    }

    if ($scope.env.adv_id != null) {
      initView();
    } else {
      initListing($scope.env.search_id);
    }
  }
})();


(function () {
    'use strict';
    angular.module('privateApp').controller('editController', editController);

    editController.$inject = ['$scope', 'advFactory', '$q', '$interval', '$state','$filter','$timeout'];

    function editController($scope, advFactory, $q, $interval, $state, $filter, $timeout) {
        // $scope.user = null;
        $scope.model = {
        };
        $scope.adv = {};
        var promises = [];

        $scope.env = {
            submit: false,
            id: $state.params.id,
            loading: true,
            subcats: [],
            equipments: [],
            categories: [],
            date_available: null,
            energy_source: advFactory.energy_source,
            heating: advFactory.heating,
            energy_class: advFactory.energy_class,
        };

        function initPage(deferred) {

            var advPromise = advFactory.getUserAdvById($scope.env.id).then(function (adv) {
                $scope.model = adv;
                $scope.adv = adv;

            });
            promises.push(advPromise);

            var dataSetPromise = advFactory.getDataSets().then(function (response) {
                $scope.env.subcats = response.sub_categories;
                $scope.env.equipments = response.equipments;
                $scope.env.categories = response.categories;
                $scope.env.agb = response.agb;
            });
            promises.push(dataSetPromise);


            $q.all(promises).then(function () {
                $scope.env.loading = false;
                $scope.env.category_name = getCategoryName($scope.adv.category, $scope.env.categories);
                $scope.env.move_date = moment($scope.adv.move_date).format('YYYY-MM-DD');

                initGoogleMap($scope.model.lat, $scope.model.lng);
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);

        $scope.$watch('env.move_date', function (value) {
            if (value==undefined){
                return;
            }
            $scope.model.move_date = moment(value).format('DD-MM-YYYY')
        });

        $scope.$watch('model', function (value) {
            console.log(value)
        },true );

        $scope.save = function (data, form) {
            $scope.env.submit = true;
            if (!form.$invalid ) {
                $scope.env.send = true;
                $scope.adv.update(data).then(function (response) {
                        $scope.env.send = false;
                        alertify.success( $filter('translate')("Advert changed") );
                        $timeout(function () {
                            $state.go("my-adv")
                        },2000);
                    },
                    function (error) {
                        $scope.env.send = false;
                        console.log(error)
                    })
            }

        };

        function getCategoryName(id, cats) {
            for (var i in cats) {
                if (cats[i].id == id) {
                    return cats[i].title
                }
            }
        }

        function initGoogleMap(lat, lng) {
            if (lat == null || lng == null) {
                return;
            }
            var interval = $interval(function () {
                if (document.getElementById('map')) {
                    var map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: lat * 1, lng: lng * 1},
                        zoom: 18
                    });
                    new google.maps.Marker({
                        position: {lat: lat * 1, lng: lng * 1},
                        map: map

                    });
                    $interval.cancel(interval);
                }
            }, 1000)
        }
    }
})();


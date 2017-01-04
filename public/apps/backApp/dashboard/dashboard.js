(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('dashboard', dashboard);

    dashboard.$inject = ['$scope', '$filter', '$q', 'categoryFactory','pointFactory'];

    function dashboard($scope, $filter, $q, categoryFactory, pointFactory) {
        $scope.env = {
            points: [],
            categories: [],
            source_points: [],
            display_points: [],
            page: 1,
            submit: false,
            edit: null,
            create: false
        };
        $scope.filter = {};
        $scope.model = {};


        $scope.fileSelected = function (value) {
            if ( !angular.isArray(value) ){
                return;
            }
            $scope.env.submit = true;
            pointFactory.upload(value[0]).then(function (response) {
                alertify.success('Import completed');
                $scope.env.submit = false;
                loadData()
            },function (response) {
                alertify.error('Import fail');
                $scope.env.submit = false;

            })

        };

        // $scope.$parent.init.push(initPage);

        $scope.updateStatus = function (order) {
            order.update({'status': order.status}).then(function (response) {
                alertify.success("Order Status Updated");
            })
        };

        $scope.$watch('filter', function (value) {
            $scope.env.points = filter($scope.env.source_points);
            $scope.env.pages = Math.ceil( $scope.env.points.length/15 );
            $scope.setPage(1)
        }, true);

        $scope.setPage = function (page) {
            $scope.env.page = page;
            var begin = ((page - 1) * 15)
                , end = begin + 15;

            $scope.env.display_points = $scope.env.points.slice(begin, end);
        };
        $scope.range = function (min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $scope.delete = function (point, index) {
            point.delete().then(function (response) {
                alertify.success("Point Deleted");
            });

            $scope.env.source_points = $scope.env.source_points.filter(function (p) {
                if ( p.id==point.id){
                    return false;
                }
                return true;
            });
            $scope.env.points = filter($scope.env.source_points);
            $scope.env.pages = Math.ceil( $scope.env.source_points.length/15 );
            $scope.setPage($scope.env.page)
        };

        $scope.create = function (data) {
            $scope.env.create = false;
            alertify.success("Point Created");
            $scope.model = {};
            pointFactory.store(data).then(function () {
                loadData();
            })
        };
        $scope.update = function (point) {
            $scope.env.edit = null;
            alertify.success("Point Changed");
            point.update(point).then(function () {
                loadData();
            })
        };

        function loadData() {
            categoryFactory.getAll().then(function (response) {
                $scope.env.categories = response;
            });
            pointFactory.getAll().then(function (response) {
                $scope.env.source_points = response;
                $scope.env.points = response;
                $scope.env.pages = Math.ceil( response.length/15 );
                $scope.setPage(1)
            });
        }

        function filter(data) {
            return data.filter( function (each) {
                if ( $scope.filter.category_id && each.category_id!=$scope.filter.category_id ){
                    return false;
                }
                if ( $scope.filter.key ){
                    var key = $scope.filter.key.toLowerCase();
                    if ( each.name.toLowerCase().indexOf(key)==-1 && each.address.toLowerCase().indexOf(key)==-1  && each.phone.toLowerCase().indexOf(key)==-1 ){
                        return false;
                    }
                }
                return true;
            }) ;
        }
        loadData();
    }

})();
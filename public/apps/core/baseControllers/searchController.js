(function () {
    'use strict';
    angular.module('core').controller('searchController', ['$scope', '$q', 'searchLogFactory', searchController]);


    function searchController($scope, $q, searchLogFactory) {

        $scope.search = {
            per_page: 10,
            page: 1,
            query: null,
            founds: []
        };

        var search = localStorage.getItem($scope.$parent.env.type+'_search');
        if (search != null) {
            searchLogFactory.getById(search).then(function (search) {
                $scope.search.query = search;
                $scope.$emit('search', {$scope:$scope,query:search.query});
                $scope.search.per_page = search.config.per_page;
                $scope.search.page = search.config.page;
                //$scope.env.total = search.number_of_results;
                //$scope.search($scope.filter)
            }, function () {

            });
        }

        $scope.setPerPage = function (count) {
            $scope.search.per_page = count;
            $scope.setPage(1);
        };


        $scope.setPage = function (page) {
            $scope.search.page = page;
            //$scope.searchRequest($scope.env.type);
            $scope.$emit('search', {$scope:$scope});
        };

        $scope.reset = function () {
            $scope.$emit('reset', {$scope:$scope});
        };
        $scope.searchSubmit = function () {
            $scope.$emit('search', {$scope:$scope});
        };
        $scope.range = function (min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };
        $scope.$watch('[search.total,search.per_page]', function () {
            $scope.search.pages = Math.round($scope.search.total / $scope.search.per_page);
        });

        $scope.searchRequest = function (type, data) {
            var search_defer = $q.defer();
            if ($scope.search.query) {
                    $scope.search.query.updateQuery(data).then(function () {
                        getResultFunc(type)($scope.search.page, $scope.search.per_page).then(function (response) {
                            $scope.search.total = $scope.search.query.number_of_results;
                            $scope.search.found = response.rows;
                            console.log(response)
                            search_defer.resolve(response);
                        })
                    });
            } else {
                getStoreFunc(type)(data).then(function (search) {
                    $scope.search.query = search;
                    localStorage.setItem(type+'_search', search.id);
                    getResultFunc(type)($scope.search.page, $scope.search.per_page).then(function (response) {
                        $scope.search.total = $scope.search.query.number_of_results;
                        $scope.search.found = response.rows;
                        search_defer.resolve(response);
                    })
                });
            }
            return search_defer.promise;
        };

        function getStoreFunc(type) {
            if (type == 'advert') {
                return searchLogFactory.storeAdvs;
            } else if (type == 'account') {
                return searchLogFactory.storeAccounts;

            } else if (type == 'invoice') {
                return searchLogFactory.storeInvoices;
            }
        }

        function getResultFunc(type) {
            if (type == 'advert') {
                return $scope.search.query.getAdvertResult;
            } else if (type == 'account') {
                return $scope.search.query.getAdvertResult
            } else if (type == 'invoice') {
                return $scope.search.query.getInvoiceResult
            }
        }

    }
})();


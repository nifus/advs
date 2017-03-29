(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsSearchController', ['$scope', 'searchLogFactory', '$q', '$filter', advertsSearchController]);


    function advertsSearchController($scope, searchLogFactory, $q, $filter) {
        $scope.filter = {
            adv_type: 'all',
            account: 'all',
            statuses: ['all']
        };
        $scope.env = {
            account: null,
            adv: null,
            advs: [],
            per_page: 40,
            page: 1,
            pages: 0,
            total: 0,
            selected: [],
            statuses: [
                {id: 'all', title: $filter('translate')('All')},
                {id: 'active', title: $filter('translate')('Active')},
                {id: 'expired', title: $filter('translate')('Expired')},
                {id: 'disabled', title: $filter('translate')('Disabled')},
                {id: 'blocked', title: $filter('translate')('BLOCKED')},
                {id: 'payment_waiting', title: $filter('translate')('Waiting for payment')},
                {id: 'approve_waiting', title: $filter('translate')('Waiting for approve')},
            ],
            blocked_message: 'Dear customer,',
            search: null
        };

        function initPage(deferred) {
            var promises = [];
            $scope.user = $scope.$parent.user;
            if (!$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }

            var search = localStorage.getItem('adverts_search');
            if (search != null) {
                var search_log_promise = searchLogFactory.getById(search).then(function (search) {
                    $scope.env.search = search;
                    $scope.filter = search.query;
                    $scope.env.per_page = search.config.per_page;
                    $scope.env.page = search.config.page;
                    $scope.env.total = search.number_of_results;
                    $scope.search($scope.filter)

                }, function () {

                });
                promises.push(search_log_promise);
            }

            $q.all(promises).then(function () {
                deferred.resolve();
            });


            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);


        $scope.setAdv = function (adv) {
            $scope.env.adv = adv;
        };
        $scope.closeAdv = function () {
            $scope.env.adv = null;
        };
        $scope.reset = function () {
            $scope.filter = {
                type: 'all',
                account: 'all',
                statuses: ['all']
            };
            $scope.search($scope.filter)
        };
        $scope.search = function (data) {
            $scope.env.loading = true;
            var search_defer = $q.defer();
            if ($scope.env.search) {
                $scope.env.search.updateQuery($scope.filter ).then(function () {
                    $scope.env.search.getAdvertResult($scope.env.page, $scope.env.per_page).then(function (response) {
                        $scope.env.advs = response.advs;
                        $scope.env.total = $scope.env.search.number_of_results;
                        //$scope.setAccount($scope.env.advs[0].owner);
                        //$scope.displayHistoryTab()
                        $scope.env.loading = false;
                        //$scope.setAdv($scope.env.advs[0])
                    })
                });

            } else {
                searchLogFactory.storeAdvs($scope.filter ).then(function (search) {
                    $scope.env.search = search;
                    localStorage.setItem('adverts_search', search.id);
                    $scope.env.search.getAdvertResult($scope.env.page, $scope.env.per_page).then(function (response) {
                        $scope.env.total = search.number_of_results;
                        $scope.env.advs = response.advs;
                        $scope.env.loading = false;
                    })
                });
            }
            return search_defer.promise;
        };
        $scope.setPerPage = function (count) {
            $scope.env.per_page = count;
            $scope.setPage(1);
        };


        $scope.setPage = function (page) {
            $scope.env.page = page;
            $scope.search();
        };
        $scope.range = function (min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };

        $scope.$watch('[env.total,env.per_page]', function (total) {
            $scope.env.pages = Math.round($scope.env.total / $scope.env.per_page);
        });

        $scope.setAccount = function (account) {
            $scope.env.account = account;
        };
        $scope.closeAccount = function () {
            $scope.env.account = null;
        }
    }

})();
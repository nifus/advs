(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('accountsSearchController', accountsSearchController);

    accountsSearchController.$inject = ['$scope', 'userFactory', '$q', 'searchLogFactory', '$filter'];

    function accountsSearchController($scope, userFactory, $q, searchLogFactory, $filter) {
        $scope.filter = {
            adv_type: 'all',
            account: 'all',
            statuses: ['all']
        };
        $scope.env = {
            tab:'history',
            user: null,
            users: [],
            per_page: 40,
            page: 1,
            // blocked_message:'Dear customer,',
            countries: [],
            pages: 0,
            total: 0,
            selected: [],
            selected_advs: [],
            search: null,
            loading: true,
            blocked_flag: 0,
            block_this_account_flag: 0,
            edititable: false,

        };


        function initPage(deferred) {

            var promises = [];
            $scope.user = $scope.$parent.user;


            var countries_promise = userFactory.getCountries().then(function (response) {
                $scope.env.countries = response
            });
            promises.push(countries_promise);

            var search = localStorage.getItem('accounts_search');
            if (search != null) {
                var search_log_promise = searchLogFactory.getById(search).then(function (search) {
                    $scope.env.search = search;
                    $scope.filter = search.query;
                    $scope.env.per_page = search.config.per_page;
                    $scope.env.page = search.config.page;
                    $scope.env.total = search.number_of_results;
                });
                promises.push(search_log_promise);
            }

            $q.all(promises).then(function () {
                $scope.search($scope.filter).then(function () {
                    $scope.env.loading = false;
                });
                return deferred.promise;
            })
        }

        $scope.$parent.init.push(initPage);



        $scope.close = function () {
            $scope.env.user = null;
        };
        $scope.reset = function () {
            $scope.filter = {
                adv_type: 'all',
                account: 'all',
                statuses: ['all']
            };
            //$scope.search($scope.filter)
            $scope.setPage(1);
        };
        $scope.search = function (data) {
            $scope.env.loading = true;
            var search_defer = $q.defer();

            if ($scope.env.search) {
                $scope.env.search.update(data).then(function () {
                    $scope.env.search.getAccountResults($scope.env.page, $scope.env.per_page).then(function (users) {
                        $scope.env.users = users;
                        $scope.env.total = $scope.env.search.number_of_results;
                        $scope.setUser($scope.env.users[0])
                        $scope.env.loading = false;
                    })
                });

            } else {
                searchLogFactory.storeAccounts(data).then(function (search) {
                    $scope.env.search = search;
                    localStorage.setItem('accounts_search', search.id);
                    $scope.env.search.getAccountResults($scope.env.page, $scope.env.per_page).then(function (users) {
                        $scope.env.total = search.number_of_results;
                        $scope.env.users = users;
                        $scope.env.loading = false;
                    })
                });
            }


            /*userFactory.search($scope.env.page,$scope.env.per_page, data).then(function (response) {
             console.log(response)
             $scope.env.total = response.total;
             $scope.env.users = response.rows;

             });*/
            return search_defer.promise;
        };
        $scope.setPerPage = function (count) {
            $scope.env.per_page = count;
            $scope.setPage(1);
        };


        $scope.$watch('filter', function (value) {
            //  console.log(value)
        }, true);

        $scope.changeListStatuses = function (id) {
            // console.log(id)
        }


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

        $scope.servicePage = function (service) {
            $state.go('service-page', {id: service.id})
        };
        $scope.$watch('[env.total,env.per_page]', function (total) {
            $scope.env.pages = Math.round($scope.env.total / $scope.env.per_page);
        });
        $scope.activateSelectedAccounts = function () {
            alertify.confirm($filter('translate')("Do you want to activate selected accounts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in $scope.env.selected) {
                        promise = $scope.env.selected[i].activate();
                        promises.push(promise);
                    }
                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected accounts is activated'));
                        $scope.env.selected = [];
                    })
                }
            });
        };
        $scope.activateAccount = function (user) {
            alertify.confirm($filter('translate')("Do you want to activate this account?"), function (e) {
                if (e) {
                    user.activate().then(function () {
                        alertify.success($filter('translate')('Account is activated'));
                    })
                }
            });
        };
        $scope.saveAccount = function (user) {
            alertify.confirm($filter('translate')("Do you want to change this account?"), function (e) {
                if (e) {
                    user.update().then(function () {
                        alertify.success($filter('translate')('Account is changed'));
                        $scope.env.edititable = false;

                    })
                }
            });
        };
        $scope.deletedAccount = function (user) {
            alertify.confirm($filter('translate')("Do you want to delete this account?"), function (e) {
                if (e) {
                    user.update().then(function () {
                        alertify.success($filter('translate')('Account is deleted'));
                        $scope.close()
                    })
                }
            });
        };

        $scope.deleteSelectedAdvs = function (advs) {
            alertify.confirm($filter('translate')("Do you want to delete selected adverts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in advs) {
                        promise = advs[i].delete();
                        promises.push(promise);
                    }
                    $scope.env.user.advs = $scope.env.user.advs.filter(function (adv) {
                        for (var i in advs) {
                            if (adv.id==advs[i].id){
                                return false;
                            }
                            return true;
                        }
                    })
                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected adverts is deleted'));
                        $scope.env.selected_advs = [];
                    })
                }
            });
        };
        $scope.activateSelectedAdvs = function (advs) {
            alertify.confirm($filter('translate')("Do you want to activate selected adverts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in advs) {
                        promise = advs[i].activate();
                        promises.push(promise);
                    }

                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected adverts is activated'));
                        $scope.env.selected_advs = [];
                    })
                }
            });
        };

        $scope.blockSelectedAdvs = function (advs) {
            alertify.confirm($filter('translate')("Do you want to block selected adverts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in advs) {
                        promise = advs[i].block();
                        promises.push(promise);
                    }

                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected adverts is blocked'));
                        $scope.env.selected_advs = [];
                    })
                }
            });
        };

        $scope.setUser = function (user) {
            $scope.env.user = user;
            $scope.env.user.getAdvs().then(function (advs) {
                $scope.env.user.advs = advs;
            })
        };

        $scope.deleteSelectedAccounts = function () {
            alertify.confirm($filter('translate')("Do you want to delete selected accounts?"), function (e) {
                if (e) {
                    var promises = [];
                    var promise;
                    for (var i in $scope.env.selected) {
                        promise = $scope.env.selected[i].delete();
                        promises.push(promise);
                        $scope.env.users = $scope.env.users.filter(function (user) {
                            if ( user.id==$scope.env.selected[i].id){
                                return false;
                            }
                            return true;
                        })
                    }
                    $q.all(promises).then(function () {
                        alertify.success($filter('translate')('All selected accounts is deleted'));
                        $scope.env.selected = [];

                    })
                }
            });
        }

    }

})();
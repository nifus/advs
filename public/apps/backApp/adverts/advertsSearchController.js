(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsSearchController', advertsSearchController);

    advertsSearchController.$inject = ['$scope', 'advFactory','searchLogFactory', '$q', '$filter'];

    function advertsSearchController($scope, advFactory,searchLogFactory, $q, $filter) {
        $scope.filter = {
            adv_type: 'all',
            account: 'all',
            statuses: ['all']
        };
        $scope.env = {
            adv: null,
            advs:[],
            per_page: 40,
            statuses: [
                {id: 'all', title:$filter('translate')('All')},
                {id: 'active', title:$filter('translate')('Active')},
                {id: 'expired', title:$filter('translate')('Expired')},
                {id: 'disabled', title:$filter('translate')('Disabled')},
                {id: 'blocked', title:$filter('translate')('BLOCKED')},
                {id: 'payment_waiting', title:$filter('translate')('Waiting for payment')},
                {id: 'approve_waiting', title:$filter('translate')('Waiting for approve')},
            ],
            blocked_message:'Dear customer,' +
            ''
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;
            if ( !$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }
            var search_promise = $scope.search($scope.filter);


            $q.all([search_promise]).then(function () {
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
                adv_type: 'all',
                account: 'all',
                statuses: ['all']
            };
            $scope.search($scope.filter)
        };
        $scope.search = function (data) {
            var advs_defer = $q.defer();
            searchLogFactory.storeAccounts(data).then(function (response) {
                advFactory.getResult(response.id,[]).then( function (response) {
                    $scope.env.advs = response.advs;
                    advs_defer.resolve(response.advs)
                });
            })
            return advs_defer.promise;
        };
        $scope.setPerPage = function (count) {
            $scope.env.per_page = count;
        };

        $scope.$watch('filter', function (value) {
            console.log(value)
        },true);

        $scope.changeListStatuses = function (id) {
            console.log(id)
        }
    }

})();
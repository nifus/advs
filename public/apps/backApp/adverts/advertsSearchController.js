(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsSearchController', ['$scope', '$filter', advertsSearchController]);


    function advertsSearchController($scope, $filter) {
        $scope.filter = {
            type: 'all',
            account: 'all',
            statuses: ['all']
        };

        $scope.env = {
            type: 'advert',
            account: null,
            adv: null,
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
            $scope.user = $scope.$parent.user;
            if (!$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }
            return false
        }

        $scope.$parent.init.push(initPage);


        $scope.setAdv = function (adv) {
            $scope.env.adv = adv;
        };
        $scope.closeAdv = function () {
            $scope.env.adv = null;
        };

        $scope.$on('reset', function (e) {
            reset();
            $scope.result(e.targetScope);
        });

        $scope.$on('search', function (e, params) {
            if (params.query) {
                $scope.filter = params.query
            }
            $scope.result(params.$scope);
        });

        $scope.result = function (search_scope) {
            $scope.env.loading = true;
            search_scope.searchRequest($scope.env.type, $scope.filter).then(function (response) {
                $scope.env.loading = false;
            });
        };

        $scope.setAccount = function (account) {
            $scope.env.account = account;
        };

        $scope.closeAccount = function () {
            $scope.env.account = null;
        };


        function reset() {
            $scope.filter = {
                type: 'all',
                account: 'all',
                statuses: ['all']
            };
        }
    }

})();
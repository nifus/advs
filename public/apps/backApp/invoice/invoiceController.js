(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('invoiceController', ['$scope', 'userFactory', '$q', '$state', '$filter', invoiceController]);


    function invoiceController($scope, userFactory, $q, $state, $filter) {
        $scope.env = {
            loading: true,
            type: 'invoice'
        };
        $scope.filter = {

        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if ( !$scope.user.hasPermission('bookkeeping')) {
                $state.go('sign_in');
                return;
            }

            var countries_promise = userFactory.getCountries().then(function (response) {
                $scope.env.countries = response
            });

            $q.all([countries_promise]).then(function () {
                deferred.resolve();
                $scope.env.loading = false
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);


        $scope.$on('search', function (e) {
            $scope.result(e.targetScope);
        });
        $scope.result = function (search_scope) {
            $scope.env.loading = true;
            search_scope.searchRequest($scope.env.type,$scope.filter).then(function (response) {
                $scope.env.loading = false;
                console.log(response)
                //$scope.env.advs = response.advs;
                //$scope.env.total = $scope.env.search.number_of_results;
                //$scope.env.loading = false;
            });

        }
    }

})();
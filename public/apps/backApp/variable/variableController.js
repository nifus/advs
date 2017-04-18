(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('variableController', ['$scope', '$http', '$q', '$state', '$filter', variableController]);


    function variableController($scope, $http, $q, $state, $filter) {
        $scope.selected = [];

        $scope.env = {
            variables: [],
            loading: true
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;

            if (!$scope.user.hasPermission('variables')) {
                $state.go('sign_in');
                return;
            }
            var variables_promise = $http.get('/api/variables').then(function (response) {
                $scope.env.variables = response.data;
            });

            $q.all([variables_promise]).then(function () {
                deferred.resolve();
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        $scope.setInit(initPage);

        $scope.save = function (variable) {
            $http.post('/api/variables/' + variable.id, {value: variable.value}).then(function (response) {
                    alertify.success($filter('translate')('Variable was changed'));
                    for( var i in $scope.env.variables ){
                        if ($scope.env.variables[i].id==variable.id){
                            $scope.env.variables[i] = response.data;
                        }
                    }
                for( var i in $scope.selected ){
                    if ($scope.selected[i] == variable.id ){
                        $scope.selected.splice(i,1)
                    }
                }

                },
                function () {
                    alertify.success($filter('translate')('Error'));
                })

        };
    }

})();
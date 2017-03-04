(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('dashboard', dashboard);

    dashboard.$inject = ['$scope', '$filter', '$q'];

    function dashboard($scope, $filter, $q) {
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



    }

})();
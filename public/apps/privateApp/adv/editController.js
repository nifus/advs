(function () {
    'use strict';
    angular.module('privateApp').controller('editController', ['$scope', 'advFactory', '$q', '$state', '$filter', '$timeout', editController]);


    function editController($scope, advFactory, $q, $state, $filter, $timeout) {

        var promises = [];

        $scope.env = {
            action: 'form',
            submit: false,
            id: $state.params.id,
            react: $state.params.react,
            display_form: !$state.params.react == 1,
            loading: true,

        };

        function initPage(deferred) {

            var advPromise = advFactory.getById($scope.env.id).then(function (adv) {
                $scope.model = adv;
            });
            promises.push(advPromise);

            $q.all(promises).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.save = function (data, form) {
            $scope.env.submit = true;
            if (!form.$invalid) {
                $scope.env.send = true;
                $scope.adv.update(data).then(
                    function (response) {
                        $scope.env.send = false;
                    },
                    function (error) {
                        $scope.env.send = false;
                        console.log(error)
                    })
            }

        };


    }
})();


(function () {
    'use strict';
    angular.module('privateApp').controller('previewController', previewController);

    previewController.$inject = ['$scope', 'advFactory', '$q', '$filter','$state'];

    function previewController($scope, advFactory,  $q, $filter, $state) {
       // $scope.user = null;
        $scope.adv = null;
        $scope.env = {
            id: $state.params.id,
            loading: true

        };

        function initPage(deferred) {
            //$scope.user = $scope.$parent.env.user;

            var advPromise = advFactory.getUserAdvById($scope.env.id).then(function(adv){
                $scope.adv = adv;
                console.log(adv)
            },function(error){
                alert(error)
            });


            $q.all([advPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);








    }
})();


(function () {
    'use strict';
    angular.module('privateApp').controller('reactController', reactController);

    reactController.$inject = ['$scope', 'advFactory', '$q', '$filter','$state'];

    function reactController($scope, advFactory,  $q, $filter, $state) {
       // $scope.user = null;
        $scope.adv = null;
        $scope.env = {
            id: $state.params.id,
            loading: true

        };

        function initPage(deferred) {
            var advPromise = advFactory.getUserAdvById($scope.env.id).then(function(adv){
                $scope.adv = adv;
            });


            $q.all([advPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);

        $scope.editAdv = function(adv){
            $state.go('adv-edit',{'id':adv.id})
        };

        $scope.deleteAdv = function(adv){
            alertify.confirm("<h4>Delete this advert</h4><p>Are you sure you want to delete your advert?</p> <br> <p>Your advert will be deleted instantly. <br>Please note that this operation can NOT be revoked.</p>", function (e) {
                if (e) {
                    adv.delete().then(function(){

                        alertify.success( 'Your advert deleted' );

                    },function(error){
                        alertify.error( error );
                    })
                }
            });
        };




    }
})();


(function () {
    'use strict';
    angular.module('privateApp').controller('myController', myController);

    myController.$inject = ['$scope', 'userFactory', '$q', '$filter','$state'];

    function myController($scope, userFactory,  $q, $filter, $state) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            advs: [],
            loading: true,
            news: [],
            stat: {
                rent: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                },
                sell: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                }
            },
            filters:{
                type: 'all'
            },
            order: 'created_down'
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;





            var advPromise = $scope.user.getAdvs().then(function (result) {
                result = $filter('orderBy')(result,'-created_at');

                $scope.env.advs = {
                    rent: $filter('filter')(result,{type:'rent'}),
                    sell: $filter('filter')(result,{type:'sell'})
                };
            });

            $q.all([advPromise]).then(function () {
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.logout = function () {
            userFactory.logout();
            window.location.reload(true)
        };


        $scope.deleteAdv = function(adv){
            alertify.confirm("<h4>Delete this advert</h4><p>Are you sure you want to delete your advert?</p> <br> <p>Your advert will be deleted instantly. <br>Please note that this operation can NOT be revoked.</p>", function (e) {
                if (e) {
                    adv.delete().then(function(){
                        $scope.env.advs.rent = $scope.env.advs.rent.filter( function (cur_adv) {
                            if (cur_adv.id!=adv.id ){
                                return true;
                            }
                            return false;
                        });
                        $scope.env.advs.sell = $scope.env.advs.sell.filter( function (cur_adv) {
                            if (cur_adv.id!=adv.id ){
                                return true;
                            }
                            return false;
                        });
                        alertify.success( 'Your advert deleted' );

                    },function(error){
                        alertify.error( error );
                    })
                } 
            });
        };

        $scope.disableAdv = function(adv){
            alertify.confirm("<h4>Disable this advert</h4><p>Are you sure you want to disable your advert?</p> <br> <p>Please keep in mind, that the duration will keep running</p>", function (e) {
                if (e) {
                    adv.disable().then(function(){

                        alertify.success( 'Your advert disabled' );

                    },function(error){
                        alertify.error( error );
                    })
                }
            });
        };
        $scope.enableAdv = function(adv){
            alertify.confirm("<h4>Enable this advert</h4><p>Are you sure you want to anable your advert?</p> <br> <p>Please keep in mind, that the duration will keep running</p>", function (e) {
                if (e) {
                    adv.enable().then(function(){
                        alertify.success( 'Your advert enabled' );
                    },function(error){
                        alertify.error( error );
                    })
                }
            });
        };

        $scope.editAdv = function(adv){
            $state.go('adv-edit',{'id':adv.id})
        };

        $scope.$watch('env.order', function(value){
            switch(value){
                case('price_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-total_rent',1);
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-total_rent',1);
                    break;
                case('price_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-total_rent');
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-total_rent');

                    break;
                case('created_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-created_at',1);
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-created_at',1);
                    break;
                case('created_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent,'-created_at');
                    $scope.env.advs.sell = $filter('orderBy')($scope.env.advs.sell,'-created_at');

                    break;
            }

        })


    }
})();


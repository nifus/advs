(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsBlockedController', advertsBlockedController);

    advertsBlockedController.$inject = ['$scope', 'advFactory', '$q', '$filter'];

    function advertsBlockedController($scope, advFactory, $q, $filter) {


        $scope.filter = {
            type: 'all',
            account: 'all',
            status: 'all',
        };
        $scope.env = {
            source_adverts: [],
            adverts: [],
            blocked_flag: 0,
            action: 'blocked'
        };
        $scope.selected = [];

        function initPage(deferred) {
            var promises = [];
            $scope.user = $scope.$parent.user;
            if (!$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }

            var reportedPromise = advFactory.getBlocked().then(function (response) {
                $scope.env.adverts = response;
                $scope.env.source_adverts = response;
            });
            promises.push(reportedPromise);

            $q.all(promises).then(function () {
                deferred.resolve();
            });


            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

        $scope.deleteSelectedAdverts = function () {
            var promises = [];
            var removed_ids = [];
            for( var i in $scope.selected){
                promises.push($scope.selected[i].delete());
                removed_ids.push($scope.selected[i].id);
            }
            for( var i in $scope.env.adverts){
                if ( removed_ids.indexOf($scope.env.adverts[i].id)!=-1 ){
                    $scope.env.adverts.splice(i,1);
                }
            }
            $q.all(promises).then(function () {
                alertify.success($filter('translate')('All selected adverts is deleted'));
            });
        };
        $scope.blockAdvert = function (advert, msg) {
            advert.block(msg).then(function () {
                alertify.success($filter('translate')('Advert is blocked'));
            });

            $scope.closeAdvert();
        };


        $scope.reset = function () {
            $scope.filter = {
                type: 'all',
                account: 'all',
                status: 'all',
            };
            $scope.search()
        };
        $scope.search = function () {
            var result = [];
            for( var i in $scope.env.source_adverts ){
                if ( $scope.filter.type!='all'){
                    if ( $scope.env.source_adverts[i].type!=$scope.filter.type ){
                        continue;
                    }
                }

                if ( $scope.filter.account!='all'){
                    if ( $scope.env.source_adverts[i].owner.group_id!=$scope.filter.account ){
                        continue;
                    }
                }

                if ( $scope.filter.status!='all'){
                    if ( $scope.env.source_adverts[i].status!=$scope.filter.status ){
                        continue;
                    }
                }
                result.push($scope.env.source_adverts[i])
            }
            $scope.env.adverts = result;
        }


        $scope.selectAdvert = function (advert) {
            $scope.env.advert = advert;
            $scope.env.action = 'advert';
            $scope.env.block_flag = 1
        };
        $scope.selectUser = function (user) {
            $scope.env.user = user;
            $scope.env.action = 'user'
        };
        $scope.closeAccount = function () {
            $scope.env.action = 'blocked';
            $scope.env.user = null;
        };
        $scope.closeAdvert = function () {
            $scope.env.action = 'blocked';
            $scope.env.advert = null;
        };
        $scope.activateAdvert = function (advert) {
            advert.activate().then(function () {
                alertify.success($filter('translate')('Advert is activated'));
            });
            $scope.closeAdvert();
            for( var i in $scope.env.adverts){
                if ( advert.id==$scope.env.adverts[i].id ){
                    $scope.env.adverts.splice(i,1);
                }
            }
        };




    }

})();
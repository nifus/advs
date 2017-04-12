(function () {
    'use strict';
    angular
        .module('backApp')
        .controller('advertsReportedController', advertsReportedController);

    advertsReportedController.$inject = ['$scope', 'advFactory', '$q', '$filter'];

    function advertsReportedController($scope, advFactory, $q, $filter) {
        $scope.filter = {
            adv_type: 'all',
            account: 'all',
        };
        $scope.env = {
            source_adverts: [],
            adverts: [],
            delete_flag: 1,
            block_flag: 1,
            action: 'reports',
            user: null,
            advert: null,
            block_message:'Dear customer,' + "\n\r"+
            'This advert was blocked because it violates our policy.' + "\n\r"+
            'Your advert description contain bad words.' + "\n\r"+
            'Please check and correct your advert.' + "\n\r"+
            'Thanks for your understanding.'
        };

        $scope.selected = [];

        function initPage(deferred) {
            var promises = [];
            $scope.user = $scope.$parent.user;
            if (!$scope.user.hasPermission('advert')) {
                $state.go('sign_in');
                return;
            }

            var reportedPromise = advFactory.getReports().then(function (response) {
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

        $scope.selectAdvert = function (advert) {
            $scope.env.advert = advert;
            $scope.env.action = 'advert'
        };
        $scope.selectUser = function (user) {
            $scope.env.user = user;
            $scope.env.action = 'user'
        };
        $scope.closeAccount = function () {
            $scope.env.action = 'reports';
            $scope.env.user = null;
        };
        $scope.closeAdvert = function () {
            $scope.env.action = 'reports';
            $scope.env.advert = null;
        };
        $scope.activateAdvert = function (advert) {
            advert.activate().then(function () {
                alertify.success($filter('translate')('Advert is activated'));
            })
        };

        $scope.blockAdvert = function (advert, msg) {
            advert.block(msg).then(function () {
                alertify.success($filter('translate')('Advert is blocked'));
            });
            for( var i in $scope.env.adverts){
                if ( advert.id==$scope.env.adverts[i].id ){
                    $scope.env.adverts.splice(i,1);
                }
            }
            $scope.closeAdvert();
        };
        $scope.blockSelectedAdverts = function () {
            var promises = [];
            var removed_ids = [];
            for( var i in $scope.selected){
                promises.push($scope.selected[i].block());
                removed_ids.push($scope.selected[i].id);
            }
            for( var i in $scope.env.adverts){
                if ( removed_ids.indexOf($scope.env.adverts[i].id)!=-1 ){
                    $scope.env.adverts.splice(i,1);
                }
            }
            $q.all(promises).then(function () {
                alertify.success($filter('translate')('All selected adverts is blocked'));
            });
        };

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
                alertify.success($filter('translate')('All selected adverts is removed'));
            });
        };

        $scope.removeSelectedReports = function () {
            var promises = [];
            var removed_ids = [];
            for( var i in $scope.selected){
                promises.push($scope.selected[i].removeReports());
                removed_ids.push($scope.selected[i].id);
            }
            for( var i in $scope.env.adverts){
                if ( removed_ids.indexOf($scope.env.adverts[i].id)!=-1 ){
                    $scope.env.adverts.splice(i,1);
                }
            }
            $q.all(promises).then(function () {
                alertify.success($filter('translate')('All selected reports is removed'));
            });
        }

        $scope.reset = function () {
            $scope.filter = {
                adv_type: 'all',
                account: 'all',
            };
            $scope.search()
        };
        $scope.search = function () {
            var result = [];
            for( var i in $scope.env.source_adverts ){
                if ( $scope.filter.adv_type!='all'){
                    if ( $scope.env.source_adverts[i].type!=$scope.filter.adv_type ){
                        continue;
                    }
                }

                if ( $scope.filter.account!='all'){
                    if ( $scope.env.source_adverts[i].owner.group_id!=$scope.filter.account ){
                        continue;
                    }
                }
                result.push($scope.env.source_adverts[i])
            }
            $scope.env.adverts = result;
        }
    }

})();
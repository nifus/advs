(function (angular) {
    'use strict';


    function accountPreviewDirective() {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            link: accountPreviewLink,
            controller: accountPreviewController,
            templateUrl: '/apps/directives/accountPreview/accountPreview.html',
            scope: {
                user: '=',
            }
        };

        function accountPreviewLink($scope, element) {


        }


        function accountPreviewController($scope) {

            $scope.env = {
                tab:'main',
                loading: false,
                adverts: [],
                advert: null
            };

            $scope.displayAdvertsTab = function () {
                $scope.env.tab='adverts';
                $scope.env.loading = true;
                $scope.user.getAdvs().then(function (response) {
                    $scope.env.adverts = response;
                    $scope.env.loading = false;
                })
            };
            $scope.displayHistoryTab = function () {
                $scope.env.tab='history';
                $scope.env.loading = true;
                $scope.user.getEventsLog().then(function (response) {
                    $scope.user.events_log = response.data;
                    $scope.env.loading = false;
                })
            };
            $scope.blockSelectedAdvs = function (advs) {
                alertify.confirm($filter('translate')("Do you want to block selected adverts?"), function (e) {
                    if (e) {
                        var promises = [];
                        var promise;
                        for (var i in advs) {
                            promise = advs[i].block();
                            promises.push(promise);
                        }

                        $q.all(promises).then(function () {
                            alertify.success($filter('translate')('All selected adverts is blocked'));
                            $scope.env.selected_advs = [];
                        })
                    }
                });
            };
            $scope.setAdvert = function (advert) {
                $scope.env.advert = advert;
            }
            $scope.closeAdvert = function () {
                $scope.env.advert = null;
            }
        }



    }

    angular.module('core').directive('accountPreview', [ '$interval', '$cookies', accountPreviewDirective]);


})(window.angular);

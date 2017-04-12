(function () {
    'use strict';
    angular.module('privateApp').controller('myController', ['$scope', 'userFactory', '$q', '$filter', '$state', '$window', myController]);


    function myController($scope, userFactory, $q, $filter, $state, $window) {
        $scope.promises = null;
        $scope.translate = $filter('translate');
        $scope.env = {
            advert: null,
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
                sale: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                }
            },
            filters: {
                type: 'all'
            },
            order: 'end_date_up'
        };

        function initPage(deferred) {
            $window.document.title = $filter('translate')('My Advertisements');


            var advPromise = $scope.user.getAdvsByCurrentUser().then(function (result) {
               // result = $filter('orderBy')(result, '-created_at');

                $scope.env.advs = {
                    rent: $filter('filter')(result, {type: 'rent'}),
                    sale: $filter('filter')(result, {type: 'sale'})
                };
                sort( $scope.env.order )
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


        $scope.deleteAdv = function (adv) {
            alertify.confirm( $filter('translate')("<h4>Delete this advert</h4><p>Are you sure you want to delete your advert?</p> <br> <p>Your advert will be deleted instantly. <br>Please note that this operation can NOT be revoked.</p>"), function (e) {
                if (e) {
                    adv.delete().then(function () {
                        $scope.env.advs.rent = $scope.env.advs.rent.filter(function (cur_adv) {
                            if (cur_adv.id != adv.id) {
                                return true;
                            }
                            return false;
                        });
                        $scope.env.advs.sale = $scope.env.advs.sale.filter(function (cur_adv) {
                            if (cur_adv.id != adv.id) {
                                return true;
                            }
                            return false;
                        });
                        alertify.success( $filter('translate')('Your advert deleted') );

                    }, function (error) {
                        alertify.error(error);
                    })
                }
            });
        };

        $scope.disableAdv = function (adv) {
            alertify.confirm( $filter('translate')("<h4>Disable this advert</h4><p>Are you sure you want to disable your advert?</p> <br> <p>Please keep in mind, that the duration will keep running</p>"), function (e) {
                if (e) {
                    adv.disable().then(function () {
                        alertify.success( $filter('translate')('Your advert disabled'));
                    }, function (error) {
                        alertify.error(error);
                    })
                }
            });
        };

        $scope.activateAdv = function (adv) {
            alertify.confirm($filter('translate')("<h4>Activate this advert</h4><p>Are you sure you want to activate your advert?</p> <br> <p>Please keep in mind, that the duration will keep running</p>"), function (e) {
                if (e) {
                    adv.activate().then(function () {
                        alertify.success( $filter('translate')('Your advert activated') );
                    }, function (error) {
                        alertify.error(error);
                    })
                }
            });
        };

        $scope.editAdv = function (adv) {
            $state.go('adv-edit', {'id': adv.id})
        };

        $scope.reactAdv = function (adv) {
            $state.go('adv-react', {'id': adv.id, react: 1})
        };

        $scope.setAdvert = function (adv) {
            $scope.env.advert = adv;
        };
        $scope.goBack = function () {
            $scope.env.advert = null;
        };

        $scope.$watch('env.order', function (value) {
            sort( value )
        });

        function sort(key) {
            switch (key) {
                case('price_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent, '-total_rent', 1);
                    $scope.env.advs.sale = $filter('orderBy')($scope.env.advs.sale, '-total_rent', 1);
                    break;
                case('end_date_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent, '-DisableDateWithTime', 1);
                    $scope.env.advs.sale = $filter('orderBy')($scope.env.advs.sale, '-DisableDateWithTime', 1);
                    break;
                case('price_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent, '-total_rent');
                    $scope.env.advs.sale = $filter('orderBy')($scope.env.advs.sale, '-total_rent');

                    break;
                case('created_up'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent, '-created_at', 1);
                    $scope.env.advs.sale = $filter('orderBy')($scope.env.advs.sale, '-created_at', 1);
                    break;
                case('created_down'):
                    $scope.env.advs.rent = $filter('orderBy')($scope.env.advs.rent, '-created_at');
                    $scope.env.advs.sale = $filter('orderBy')($scope.env.advs.sale, '-created_at');
                    break;
            }
        }

    }
})();


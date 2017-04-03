(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', ['$scope', 'advFactory', '$filter', '$interval', '$q', 'tariffFactory', 'advPaymentFactory', 'Upload', createAdvController]);


    function createAdvController($scope, advFactory, $filter, $interval, $q, tariffFactory, advPaymentFactory, Upload) {


        $scope.env = {
            submit: false,
            advert: null,
            restore_flag: false,
            loading: true,
            action: 'form'
        };

        var promises = [];
        $scope.model = {
            type: null,
            category: null,
            subcategory: 'Any',
            photos: [],
            address: {
                display_house: true
            },
            energy: {
                class: 'Any',
                source: ''
            },
            props: {
                pets: 'Any',
                floor_cover: 'Any',
                air_conditioner: 'By agreement',
                recommended_usage: 'Any'
            },
            finance: {
                ancillary_cost_included: 1,
            },
            author: {},
            air_conditioner: 'By agreement',
            edp_cabling: 'By agreement',
            price_type: 'Price per month',
            development: 'Developed',
            building_permission: 'Yes'
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            $scope.model.author.sex = $scope.user.sex;
            $scope.model.author.name = $scope.user.name;
            $scope.model.author.surname = $scope.user.surname;
            $scope.model.author.email = $scope.user.email;
            $scope.model.author.phone = $scope.user.phone;

            var restore_advert_id = localStorage.getItem("advert_id");
            if (restore_advert_id != null) {
                var adv_restore_promise = advFactory.restoreAdvert(restore_advert_id).then(function (response) {
                    $scope.model = response;
                    $scope.env.action = 'payment';
                    $scope.env.restore_flag = true;
                }, function () {

                });
                promises.push(adv_restore_promise);
            }

            $q.all(promises).then(function () {
                deferred.resolve();
                $scope.env.loading = false;
            });
            return deferred.promise;
        }

        $scope.$parent.init.push(initPage);

    }
})();


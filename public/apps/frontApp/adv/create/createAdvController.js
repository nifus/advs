(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', createAdvController);

    createAdvController.$inject = ['$scope', 'advFactory', '$filter'];

    function createAdvController($scope, advFactory, $filter) {


        $scope.env = {
            submit: false,
            move_date: null,
            is_business_rent: 0,
            is_business_sale: 0,
            energy_source: [
                'Geothermal energy',
                'Solar',
                'Wood',
                'Gas',
                'Oil',
                'Teleheating',
                'Electricity',
                'Coal',
                'Other',
            ],
            heating: [
                'Self-contained central heating',
                'Centralheating',
                'Teleheating',
                'Other',
            ],
            energy_class: [
                'Any',
                'A+',
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
            ],
            address: {},
            display_addr_details: false
        };

        advFactory.getDataSets().then(function(response){
            $scope.env.subcats = response.sub_categories;
            $scope.env.equipments = response.equipments;
        })


        $scope.model = {
            type: null,
            category: null,
            ancillary_cost_included: 1,
            pets: 'Any',
            images: [],
            address: {
                energy_class: 'Any',
                energy_source: '',
                heating: ''
            }
        };



        $scope.setPrivateType = function (type, category) {
            $scope.model.type = type;
            $scope.model.category = category;
            $scope.env.is_business_rent = 0;
            $scope.env.is_business_sale = 0;
        };

        $scope.setBusinessType = function (type, category) {
            $scope.model.type = type;
            $scope.model.category = category;
        };

        $scope.isBusiness = function (type) {
            $scope.model.type = null;
            $scope.model.category = null;
            if (type == 'sale') {
                $scope.env.is_business_rent = 0;
            } else {
                $scope.env.is_business_sale = 0;
            }
        };

        $scope.save = function (data) {
            $scope.env.submit = true;
            if (!$scope.adv_form.$invalid) {
                $scope.env.send = true;
                advFactory.store(data).then(function () {
                        $scope.env.send = false;
                    },
                    function (error) {
                        $scope.env.send = false;

                        console.log(error)
                    })
            }

        };

        $scope.$watch('model', function (value) {
            console.log(value)
        }, true);


        $scope.$watch('env.move_date', function (value) {
            $scope.model.move_date = $filter('date')(value, 'yyyy-MM-dd')
        });


        $scope.$watch('env.address', function (value) {
            if (value.details && value.details.address_components) {
                for (var i in value.details.address_components) {
                    var el = angular.copy(value.details.address_components[i]);

                    if (el.types.indexOf('street_number') != -1) {
                        $scope.model.address.house_number = (el.long_name);

                    }
                    if (el.types.indexOf('route') != -1) {
                        $scope.model.address.street = (el.long_name);

                    }
                    if (el.types.indexOf('locality') != -1) {
                        $scope.model.address.city = (el.long_name);

                    }

                    if (el.types.indexOf('country') != -1) {
                        $scope.model.address.country = (el.long_name);

                    }
                    if (el.types.indexOf('postal_code') != -1) {
                        $scope.model.address.zip = (el.long_name);
                    }
                }
                $scope.env.display_addr_details = true;

            }
        }, true);
    }
})();


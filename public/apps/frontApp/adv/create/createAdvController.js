(function () {
    'use strict';
    angular.module('frontApp').controller('createAdvController', createAdvController);

    createAdvController.$inject = ['$scope', 'advFactory', '$filter'];

    function createAdvController($scope, advFactory, $filter) {


        $scope.env = {
            submit: false,
            move_date: null,
            is_business_rent: 0,
            is_business_sell: 0,
            equipments: [
                "Balcony/Terrace",
                "New building",
                "Build-in kitchen",
                "Garden (shared-use)",
                "Elevator",
                "Garage/parking space",
                "Stepless access",
                "Guest toilet",
                "Cellar"
            ],
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

        $scope.types = {
            flat: [
                'Souterrain', 'Loft', 'Top floor flat', 'Downstairs flat', 'Maisonette', 'Penthouse', 'Raised ground floor', 'Terrace flat', 'Any'
            ],
            house: [
                'Single-family house', 'Mid-terrace town house', 'End-terrace town house', 'Multi-family house', 'Bungalow', 'Farmhouse', 'Semi-detached house', 'Villa', 'Castle/Palace', 'Any'
            ],
            garage: ['Outside-parking space', 'Carport', 'Garage', 'Underground carpark', 'Car park', 'Any'],
            office: ['Loft/Studio', 'Office', 'Praxis', 'Any'],
            gastronomy: ['Bar/Bistro/Cafe', 'Club/Disco', 'Restaurant', 'Hotel', 'Pension', 'Any'],
            hall: ['Hall (+open space)', 'Warehouse ( +open space)', 'Industrial building', 'Cold+storage ( +warehouse)', 'Car workshop', 'Any'],
            retail: ['Shopping Center']
        }

        $scope.setPrivateType = function (type, category) {
            $scope.model.type = type;
            $scope.model.category = category;
            $scope.env.is_business_rent = 0;
            $scope.env.is_business_sell = 0;
        };

        $scope.setBusinessType = function (type, category) {
            $scope.model.type = type;
            $scope.model.category = category;
        };

        $scope.isBusiness = function (type) {
            $scope.model.type = null;
            $scope.model.category = null;
            if (type == 'sell') {
                $scope.env.is_business_rent = 0;
            } else {
                $scope.env.is_business_sell = 0;
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


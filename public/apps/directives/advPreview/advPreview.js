(function (angular) {
    'use strict';


    function advPreviewDirective() {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            controller: advPreviewController,
            templateUrl: '/apps/directives/advPreview/advPreview.html',
            scope: {
                adv: '=',
                user: '=',
                hideContactForm: '@'
            }
        };

        advPreviewController.$inject = ['$scope', '$interval', '$cookies'];

        function advPreviewController($scope, $interval, $cookies) {
            console.log($scope.hideContactForm)
            $scope.env = {
                display_view_map: false,
                submit: false
            };
            var data = $cookies.getObject('contact');
            $scope.message = data;

            $scope.displayPhotos = function () {
                $scope.env.display_view_map = false;
            };
            $scope.displayMap = function (flag, need_update) {
                $scope.env.display_view_map = flag;
                if ($scope.env.display_view_map === true) {
                    initGoogleMapsView();
                }
            };
            function initGoogleMapsView() {

                var interval = $interval(function () {
                    if (document.getElementById('view_map')) {
                        var map = new google.maps.Map(document.getElementById('view_map'), {
                            center: {lat: $scope.adv.lat * 1, lng: $scope.adv.lng * 1},
                            zoom: 15
                        });
                        new google.maps.Marker({
                            position: {lat: $scope.adv.lat * 1, lng: $scope.adv.lng * 1},
                            map: map
                        });
                        // $scope.env.map.setCenter( {lat: $scope.adv.lat*1, lng: $scope.adv.lng*1} )
                        $interval.cancel(interval);
                    }
                }, 1000)

            }


            $scope.sendMessage = function (form, data) {
                $scope.env.submit = true;
                if (form.$invalid) {
                    return false;
                }
                $scope.adv.sendMessage(data).then(function (response) {
                    var expireDate = new Date();
                    expireDate.setDate(expireDate.getDate() + 199);
                    $cookies.putObject('contact', {
                        name: data.name,
                        sex: data.sex,
                        email: data.email,
                        phone: data.phone
                    }, {expires: expireDate});
                    $scope.env.submit = false;
                    if (response.success) {
                        alertify.success('Message send to owner adv');
                        $scope.message = {};
                    } else {
                        alertify.error(response.error);
                    }
                })
            };

        }


    }

    angular.module('core').directive('advPreview', advPreviewDirective);


})(window.angular);

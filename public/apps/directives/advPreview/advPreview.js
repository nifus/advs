(function (angular) {
    'use strict';


    function advPreviewDirective($interval) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            link: advPreviewLink,
            controller: advPreviewController,
            templateUrl: '/apps/directives/advPreview/advPreview.html',
            scope: {
                adv: '=',
                user: '=',
                hideContactForm: '@'
            }
        };

        function advPreviewLink($scope, element) {
            $scope.current = 0;

            var m_images = null;
            var p_images = null;
            var navigate = null;
            var back_navigate = null;
            var next_navigate = null;


            var interval = $interval(function(){
                console.log(1)
                if ( initDom() ){
                    $interval.cancel(interval);
                    p_images.removeClass('active');
                    $(p_images[0]).addClass('active');
                    if (p_images.length > 2) {
                        next_navigate.removeClass('hide');
                    }
                    next_navigate.on('click', function () {
                        open($scope.current + 1)
                    });
                    back_navigate.on('click', function () {
                        open($scope.current - 1)
                    });

                    angular.forEach(p_images, function (image, $index) {
                        $(image).data('number', $index)
                    });


                    p_images.on('click', function (el) {
                        open($(el.target).data('number'))
                    })
                }


            },1000);


            function initDom() {
                var el = $('#photo-block');
                m_images = el.find('div.main img');
                p_images = el.find('div.preview img');
                navigate = el.find('div.navigate');
                back_navigate = el.find('div.back');
                next_navigate = el.find('div.next');
                return p_images.length>0 ;
            }

            function open(number) {
                $scope.current = number;
                p_images.removeClass('active');
                $(p_images[number]).addClass('active');

                var src = $(p_images[number]).attr('src').replace('/preview/', '/full/');
                m_images.attr('src', src)
                if (number == 0) {
                    back_navigate.addClass('hide');
                } else {
                    back_navigate.removeClass('hide');
                }
                if (number == p_images.length - 1) {
                    next_navigate.addClass('hide');
                } else {
                    next_navigate.removeClass('hide');
                }
            }

        }

        advPreviewController.$inject = ['$scope', '$interval', '$cookies'];

        function advPreviewController($scope, $interval, $cookies) {

            $scope.env = {
                display_view_map: false,
                submit: false
            };
            var data = $cookies.getObject('contact');
            $scope.message = data;

            $scope.displayPhotos = function () {
                $scope.env.display_view_map = false;
            };
            $scope.displayMap = function (flag) {
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

            $scope.openLightboxModal = function (photo) {
                console.log(photo);
                console.log($scope.adv.photos);

                //Lightbox.openModal($scope.adv.photos, index);
            };

        }


    }

    angular.module('core').directive('advPreview', advPreviewDirective);


})(window.angular);

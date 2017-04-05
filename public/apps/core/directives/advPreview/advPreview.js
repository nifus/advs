(function (angular) {
    'use strict';


    function advPreviewDirective($interval) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            link: advPreviewLink,
            controller: advPreviewController,
            templateUrl: '/apps/core/directives/advPreview/advPreview.html',
            scope: {
                adv: '=',
                user: '=',
                hideContactForm: '@',
                preview: '@',
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

                    element.find('div.preview').on('click', 'div.preview-item',function (el) {

                        if (!$(el.currentTarget).data('number')){
                            // return;
                        }
                        open($(el.currentTarget).data('number'));

                    })



                }


            },1000);


            function initDom() {
                var el = $('#photo-block');
                m_images = el.find('div.main img');
                p_images = el.find('div.preview div.preview-item');

                navigate = el.find('div.navigate');
                back_navigate = el.find('div.back');
                next_navigate = el.find('div.next');
                m_images.on('load', function (e) {
                    el.find('div.main div.center').css('max-width', m_images.width()+'px')
                });

                el.find('div.main div.center').css('max-width', m_images.width()+'px');
                m_images.css('visibility','visible');
                //angular.forEach(p_images, function (image) {

                //})
                return p_images.length>0 ;
            }

            function open(number) {
                $scope.current = number;
                p_images.removeClass('active');

                $(p_images[number]).addClass('active');


                var src = $(p_images[number]).find('img').attr('src').replace('/preview/', '/full/');
                var img = new Image();
                img.src = src;

                $(img).on('load', function (e) {
                    $('#photo-block').find('div.main div.center').css('max-width', '100%')


                    $('#photo-block').find('div.wrapper img').remove();
                    $('#photo-block').find('div.wrapper').append($(img));
                    $('#photo-block').find('div.main div.center').css('max-width', $(img).width())
                    $(img).css('visibility','visible');

                });

               // m_images.attr('src', src);

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
                submit: false,
                send: false,
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

            if (  !$scope.adv.MainPhoto ){
                $scope.displayMap(true)
            }

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
                $scope.env.send = true;
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
                    $scope.env.send = false;

                    if (response.success) {
                        alertify.success('Message send to owner adv');
                        $scope.message = {};
                    } else {
                        alertify.error(response.error);
                    }
                })
            };

            $scope.openLightboxModal = function (photo) {

            };

        }


    }

    angular.module('core').directive('advPreview', advPreviewDirective);


})(window.angular);

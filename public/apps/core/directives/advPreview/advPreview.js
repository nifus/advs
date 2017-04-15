(function (angular) {
    'use strict';


    function advPreviewDirective($interval, $cookies, $filter) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            controller: advPreviewController,
            templateUrl: '/apps/core/directives/advPreview/advPreview.html',
            scope: {
                adv: '=',
                user: '=',
                hideContactForm: '@',
                preview: '@',
            }
        };






        function advPreviewController($scope) {

            $scope.env = {
                display: $scope.adv.MainPhoto ? 'photos' : 'map',
                submit: false,
                send: false,
                display_report: !($scope.user && $scope.user.id == $scope.adv.user_id)
            };

            $scope.report = {
                submit: false
            };
            $scope.adv.viewIncrement();
            $scope.message = $cookies.getObject('contact');

            $scope.displayReport = function () {
                $scope.env.display = 'report';
                $scope.report = {
                    submit: false,
                };
            };
            $scope.createReport = function (form) {
                $scope.report.submit = true;
                if ( !form.$invalid ){
                    $scope.adv.createReport($scope.report).then(
                        function (response) {
                            alertify.success($filter('translate')('Report created'));
                            $scope.report = {
                                submit: false
                            };
                            $scope.openDefaultBlock();
                        },
                        function (error) {
                            // $scope.env.send = false;
                            //$scope.env.submit = false;
                        }
                    )
                }
            };
            $scope.openDefaultBlock = function () {
                $scope.env.display = $scope.adv.MainPhoto ? 'photos' : 'map';
                if ($scope.env.display == 'map') {
                    $scope.displayMap()
                }else{
                    $scope.displayPhotos()
                }
            };
            $scope.displayPhotos = function () {
                $scope.env.display = 'photos';
                initPhotoBlock();
            };
            $scope.displayMap = function () {
                $scope.env.display = 'map';
                //if ($scope.env.display_view_map === true) {
                initGoogleMapsView();
                //}
            };
            $scope.openDefaultBlock();




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


            function initPhotoBlock() {
                $scope.current = 0;

                var m_images = null;
                var p_images = null;
                var navigate = null;
                var back_navigate = null;
                var next_navigate = null;


                var interval = $interval(function () {
                    if (initDom()) {
                        var element = $('#photo-block');
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

                        element.find('div.preview').on('click', 'div.preview-item', function (el) {

                            if (!$(el.currentTarget).data('number')) {
                                // return;
                            }
                            open($(el.currentTarget).data('number'));

                        })


                    }


                }, 1000);

                function initDom() {
                    var el = $('#photo-block');
                    m_images = el.find('div.main img');
                    p_images = el.find('div.preview div.preview-item');

                    navigate = el.find('div.navigate');
                    back_navigate = el.find('div.back');
                    next_navigate = el.find('div.next');
                    m_images.on('load', function (e) {
                        el.find('div.main div.center').css('max-width', m_images.width() + 'px')
                    });

                    el.find('div.main div.center').css('max-width', m_images.width() + 'px');
                    m_images.css('visibility', 'visible');
                    //angular.forEach(p_images, function (image) {

                    //})
                    return p_images.length > 0;
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
                        $(img).css('visibility', 'visible');

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
                        alertify.success($filter('translate')('Message send to owner adv'));
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

    angular.module('core').directive('advPreview', ['$interval', '$cookies', '$filter', advPreviewDirective]);


})(window.angular);

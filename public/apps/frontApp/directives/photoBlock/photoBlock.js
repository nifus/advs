(function (angular) {
    'use strict';

    function photoBlockDirective($timeout, $interval) {
        return {
            restrict: 'A',
            link: photoBlockLink
        };

        function photoBlockLink($scope, element) {
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


                    p_images.on('click', function (el) {
                        open($(el.target).data('number'))
                    })
                }


            },1000)


            function initDom() {
                 m_images = element.find('div.main img');
                 p_images = element.find('div.preview img');
                 navigate = element.find('div.navigate');
                 back_navigate = element.find('div.back');
                 next_navigate = element.find('div.next');
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


    }

    angular.module('frontApp').directive('photoBlock', photoBlockDirective);


})(window.angular);

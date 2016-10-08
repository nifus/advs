(function (angular) {
    'use strict';

    function photoBlockDirective() {
        return {
            restrict: 'A',
            link: photoBlockLink,
        };

        function photoBlockLink($scope, element) {
            $scope.current = 0;
            var m_images = element.find('div.main img');
            var p_images = element.find('div.preview img');
            var navigate = element.find('div.navigate');
            var back_navigate = element.find('div.back');
            var next_navigate = element.find('div.next');
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


            function open(number) {
                $scope.current = number;
                p_images.removeClass('active');
                $(p_images[number]).addClass('active');
                m_images.attr('src', $(p_images[number]).attr('src'))
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

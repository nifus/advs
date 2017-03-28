(function(angular) {

    'use strict';

    angular.module('angular-lightbox',[]).directive('lightbox', lightboxDirective)

    function lightboxDirective($interval) {
        return {
            restrict: 'A',
            link: lightboxLink

        };
        function lightboxLink($scope, element) {

            var init_loop = $interval(function () {
                var images = $('img.lightbox');
                $interval.cancel( init_loop );
                init(images);
            },2000);

            function init(images) {
                images.on('click', function () {
                    console.log('click')
                })
            }
        }
    }


})(angular);

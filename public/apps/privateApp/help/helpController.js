(function () {
    'use strict';
    angular.module('privateApp').controller('helpController', helpController);

    helpController.$inject = ['$scope', '$filter', '$window','faqFactory'];

    function helpController($scope, $filter, $window, faqFactory) {
        $scope.env = {
            loading: true,
            items:[],
            item: null
        };


        function initPage(deferred) {
            $window.document.title = $filter('translate')('Help');

            faqFactory.getAll().then(function (response) {
                $scope.env.loading = false;
                $scope.env.items = response;
                deferred.resolve();
            });
            return deferred.promise;
        }


        $scope.selectItem = function (item) {
            $scope.env.item = item;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


    }
})();


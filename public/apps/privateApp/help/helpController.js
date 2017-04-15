(function () {
    'use strict';
    angular.module('privateApp').controller('helpController', ['$scope', '$filter', '$window', 'faqFactory', '$q', '$http', helpController]);


    function helpController($scope, $filter, $window, faqFactory, $q, $http) {
        $scope.env = {
            loading: true,
            items: [],
            item: null,
            support_email: null,
            message : null
        };


        function initPage(deferred) {
            $window.document.title = $filter('translate')('Help');

            var faq_promise = faqFactory.getAll().then(function (response) {
                $scope.env.loading = false;
                $scope.env.items = response;
            });

            var data_set_promise = $http.get('/api/adv-data-sets').then(function (response) {
                $scope.env.support_email = response.data.support_email;
            });

            $q.all([faq_promise, data_set_promise]).then(function () {
                deferred.resolve();
            });
            return deferred.promise;
        }

        $scope.sendMessage = function (message) {
            $scope.$parent.user.sendMessage4Administrator(message);
            $scope.env.message= null;
            alertify.success($filter('translate')("Message sent"));
        };


        $scope.selectItem = function (item) {
            $scope.env.item = item;
        };

        // initPage();
        $scope.$parent.init.push(initPage);


    }
})();


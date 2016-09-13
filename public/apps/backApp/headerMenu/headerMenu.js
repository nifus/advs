(function (angular) {
    'use strict';

    function headerMenuDirective() {
        return {
            replace:true,
            restrict: 'E',
            controller:headerMenuController,
            templateUrl: 'apps/backApp/headerMenu/headerMenu.html',
            scope:{}
        };

        headerMenuController.$inject = ['$scope', 'userFactory', '$rootScope', '$timeout'];
        function headerMenuController($scope, userFactory, $rootScope, $timeout){

            $scope.env={
                showLogin: false,
                user: undefined
            };

            auth();
            $rootScope.$on('login', function() {
                auth();
            });
            $rootScope.$on('logout', function() {
                auth();
                window.location.href = '/signin';

            });
            function auth(){
                userFactory.getAuthUser().then(function(user){
                    $scope.env.user = user;
                })
            }

            $scope.logout = function(){
                userFactory.logout();
            }
        }


    }

    angular.module('backApp').directive('headerMenu', headerMenuDirective);


})(window.angular);

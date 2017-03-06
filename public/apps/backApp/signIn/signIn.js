(function (angular) {
    'use strict';

    angular.module('backApp').controller('signIn', signIn);

    signIn.$inject = ['$scope', 'userFactory','$cookies','$state'];
    function signIn($scope, userFactory,$cookies,$state) {
        $scope.model = {
            email: $cookies.get('admin-email')
        };
        $scope.env = {
            waiting: false
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.user;
            if ( $scope.user && !$scope.user.isAdminAccount() ){
                $scope.user= null;
            }else if($scope.user && $scope.user.isAdminAccount()){
                $state.go('sign_in');
            }

            return deferred.promise;
        }
        $scope.$parent.init.push(initPage);



        $scope.signIn = function (email, password, remember) {
            $scope.env.waiting = true;
            userFactory.login({email: email, password: password,  is_admin: true}).then(function (response) {
                $scope.env.waiting = false;
                if (response.error != undefined) {
                    $scope.env.error = response.error
                } else {

                    var expireDate = new Date();
                    expireDate.setDate(expireDate.getDate() + 99);
                    $cookies.put('admin-email', email, {'expires': expireDate,'path':'/'});
                    if (remember===true){
                        $cookies.put('token', response.token, {'expires': expireDate,'path':'/'});
                    }else{
                        $cookies.put('token', response.token,{'path':'/'});
                    }
                    $scope.setUser(response.user);
                    $state.go('adverts-search');
                }
            })
        }
    }
})(angular);
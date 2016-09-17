(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('userFactory', userFactory);
    userFactory.$inject = [ '$http', '$rootScope','$auth','$q','$cookies'];

    function userFactory( $http, $rootScope,$auth,$q,$cookies) {

        return {
            createPrivateAccount: createPrivateAccount,

            login: login,
            logout: logout,
            isAuthenticated: isAuthenticated,


            refresh: refresh,
            getAuthUser: getAuthUser,
            getAll:getAll,
            getById:getById,
            store:store
        };


        function createPrivateAccount(data) {
            return $http.post('/api/user/private-account', data).then( function(response){
                return response.data;
            })
        }

        function logout() {
            $cookies.put('token',null)
            $auth.logout();
            $rootScope.$broadcast('logout');
        }

        function login(credentials) {
            var deferred =$q.defer();
            var promise = deferred.promise;

            $auth.login(credentials).then(function (response) {
               // $rootScope.$broadcast('login');
                deferred.resolve({success: true, token: response.data.token});

            }).catch(function (response) {
                deferred.resolve({success: false, error: response.data.error});

            });
            return promise;
        }

        function isAuthenticated() {
            if ($auth.isAuthenticated()) {
                return true;
            }
            return false;
        }






        function refresh(){
            return $http.get(window.SERVER+'/backend/user/update-token').then( function(response){
                $auth.setToken(response.data.token)
            })
        }



        /**
         * Get UserService with current user
         * @returns promise
         */
        function getAuthUser(){
            var cache  = cacheService(
                function(){
                    $http.get(window.SERVER+'/backend/user/get-auth').success( function(response){
                        cache.end( userService(response) );
                    }).error( function(response){
                        cache.end( null );
                    })
                }, 'user_getAuthUser', 20
            );
            return cache.promise;
        }

        function getAll(){
            var cache  = cacheService(
               function(){
                   $http.get(window.SERVER+'/backend/user/get-all').success(function (answer) {
                       var users = [];
                       var i;
                       for( i in answer ){
                           users.push( userService(answer[i]) );
                       }
                       cache.end( users );
                   }).error(function (data, code) {
                       cache.end({success: false, error: data.error});
                   })
               }, 'user_getAllUsers', 10
            );
            return cache.promise;
        }



        function getById(id){
            var cache  = cacheService(
                function(){
                    $http.get(window.SERVER+'/backend/user/'+id).success(function (response) {
                        cache.end( userService(response) );
                    }).error( function(response){
                        cache.end( null );
                    })
                }, 'user_getById'
            );
            return cache.promise;
        }




        function store(data) {
            return $http.post(window.SERVER+'/backend/user', data).then( function(response){
                return response.data;
            })
        }
    }


})(angular, window);




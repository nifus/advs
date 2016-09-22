(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('userFactory', userFactory);
    userFactory.$inject = [ '$http', '$rootScope','$auth','$q','$cookies','cacheService','userService'];

    function userFactory( $http, $rootScope,$auth,$q,$cookies,cacheService,userService) {

        return {
            createPrivateAccount: createPrivateAccount,
            createBusinessAccount: createBusinessAccount,
            forgot: forgot,

            login: login,
            logout: logout,
            isAuthenticated: isAuthenticated,


            refresh: refresh,
            getAuthUser: getAuthUser,
            getAll:getAll,
            getById:getById,
            store:store
        };


        function forgot(email) {
            return $http.post('/api/user/forgot-password', {email:email}).then( function(response){
                return response.data;
            })
        }
        function createPrivateAccount(data) {
            return $http.post('/api/user/private-account', data).then( function(response){
                return response.data;
            })
        }

        function createBusinessAccount(data) {
            return $http.post('/api/user/business-account', data).then( function(response){
                return response.data;
            })
        }


        function logout() {
            $cookies.put('token',null);
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

        /**
         * Get UserService with current user
         * @returns promise
         */
        function getAuthUser(){
            var cache  = cacheService(
                function(){
                    $http.get('/api/user/get-auth').success( function(response){
                        cache.end( userService(response) );
                    }).error( function(response){
                        cache.end( null );
                    })
                }, 'user_getAuthUser', 20
            );
            return cache.promise;
        }




        function refresh(){
            return $http.get(window.SERVER+'/backend/user/update-token').then( function(response){
                $auth.setToken(response.data.token)
            })
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




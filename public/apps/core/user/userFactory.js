(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('userFactory', userFactory);
    userFactory.$inject = ['$http', '$rootScope', '$auth', '$q', '$cookies', 'userService'];

    function userFactory($http, $rootScope, $auth, $q, $cookies, userService) {

        return {
            createPrivateAccount: createPrivateAccount,
            createBusinessAccount: createBusinessAccount,
            forgot: forgot,

            login: login,
            logout: logout,
            isAuthenticated: isAuthenticated,

            getAuthUser: getAuthUser,
            getAllNewBusinessUsers: getAllNewBusinessUsers,
            getAllBlockedUsers: getAllBlockedUsers,
            getAllAdministrationUsers: getAllAdministrationUsers,
            createAdministrator: createAdministrator,
            search: search,
            getCountries: getCountries
        };


        function forgot(email) {
            return $http.post('/api/user/forgot-password', {email: email}).then(function (response) {
                return response.data;
            })
        }

        function createPrivateAccount(data) {
            return $http.post('/api/user/private-account', data).then(function (response) {
                return response.data;
            })
        }

        function createBusinessAccount(data) {
            return $http.post('/api/user/business-account', data).then(function (response) {
                return response.data;
            })
        }


        function logout() {
            $cookies.put('token', null, {'path': '/'});
            $auth.logout();
            $rootScope.$broadcast('logout');
        }


        function login(credentials) {
            var deferred = $q.defer();
            var promise = deferred.promise;

            $auth.login(credentials).then(function (response) {
                // $rootScope.$broadcast('login');
                deferred.resolve({success: true, token: response.data.token, user: response.data.user});
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
        function getAuthUser() {
            var defer = $q.defer();
            $http.get('/api/user/get-auth').success(function (response) {
                defer.resolve(new userService(response));
            }).error(function (response) {
                defer.reject(response)
            });
            return defer.promise;
        }


        function createAdministrator(data) {
            var defer = $q.defer();
            $http.post('/api/user/administrator', data).then(function (response) {
                if (response.data.success==false){
                    defer.reject(response.data.error);
                }else{
                    defer.resolve(new userService(response.data));
                }
            }, function (response) {
                defer.reject(response.status+': '+response.statusText);
            });
            return defer.promise;
        }

        /* function updateAdministrator(data) {
         return $http.post( '/api/user/'+, data).then(function (response) {
         return response.data;
         })
         }*/


        function getAllNewBusinessUsers() {
            var defer = $q.defer();
            $http.get('/api/user/get-all-new-business').then(function (response) {
                var users = [];
                for (var i in response.data) {
                    users.push(new userService(response.data[i]));
                }
                defer.resolve(users);
            }, function (data, code) {
                defer.reject({success: false, error: data.error});
            });
            return defer.promise;
        }

        function getAllBlockedUsers() {
            var defer = $q.defer();
            $http.get('/api/user/get-all-blocked').then(function (response) {
                var users = [];
                for (var i in response.data) {
                    users.push(new userService(response.data[i]));
                }
                defer.resolve(users);
            }, function (data, code) {
                defer.reject({success: false, error: data.error});
            });
            return defer.promise;
        }

        function getAllAdministrationUsers() {
            var defer = $q.defer();
            $http.get('/api/user/get-all-administration').then(function (response) {
                var users = [];
                for (var i in response.data) {
                    users.push(new userService(response.data[i]));
                }
                defer.resolve(users);
            }, function (data, code) {
                defer.reject({success: false, error: data.error});
            });
            return defer.promise;
        }


        function getCountries() {
            return $http.get('/api/back/users/countries').then(function (response) {
                return response.data;
            })
        }

        function search(page, limit, filters) {
            return $http.post('/api/back/users/search', {
                'page': page,
                'limit': limit,
                'filters': filters
            }).then(function (response) {
                return response.data;
            })
        }
    }


})(angular, window);




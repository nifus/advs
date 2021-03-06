(function (angular, window) {
    'use strict';
    angular.module('core').service('userService', ['$http', 'advFactory', '$q', 'tariffFactory', userService]);

    function userService($http, advFactory, $q, tariffFactory) {
        return function (data) {
            var Object = data;
            Object.waiting = false;


            Object.blocked_message = 'Dear customer,' + "\n\r" +
                'The verification of your commercial register ID failed - this is violates our policy.' + "\n" +
                'Because of that we had to block your account.' + "\n\r" +
                'We will try to contact you to solve this issue in the next days.' + "\n" +
                'If you are in hurry, you can contact us by writing an email to service@carbay.de.' + "\n\r" +
                'Thanks for your understanding.' + "\n\r" +
                'Your carbay support team';


            Object.sBusinessAccount = function () {
                if (Object.group_id == 3) {
                    return true;
                }
                return false;
            };


            Object.isPrivateAccount = function () {
                if (Object.group_id == 2) {
                    return true;
                }
                return false;
            };

            Object.isAdminAccount = function () {
                if (Object.group_id == 1) {
                    return true;
                }
                return false;
            };

            Object.getAdvStat = function () {
                return $http.get('/api/adv/'+Object.id+'/statistics').then(function (response) {
                    return response.data;
                })
            };

            Object.getCurrentTariff = function () {
                return tariffFactory.getCurrentTariff();
            };
            Object.getFutureTariff = function () {
                return tariffFactory.getFutureTariff();
            };

            Object.buyTariff = function (tariff_id) {
                return $http.post('/api/user/tariff', {tariff_id: tariff_id}).then(function (response) {
                    return response.data;
                })
            };

            Object.changeEmail = function (data) {
                return $http.put('/api/user/change-email', data)
            };
            Object.sendConfirmCode = function (email) {
                return $http.post('/api/user/send-confirm-code', {email: email})
            };

            Object.changePassword = function (data) {
                return $http.put('/api/user/change-password', data)
            };
            Object.changeAllowNotifications = function (data) {
                return $http.put('/api/user/allow-notifications', {allow_notifications: data})
            };
            Object.deleteAccount = function () {
                return $http.delete('/api/user')
            };
            Object.changePayment = function (data) {
                return $http.put('/api/user/change-payment', data)
            };
            Object.changeContactData = function (data) {
                return $http.put('/api/user/change-contact-data', data)
            };

            Object.activateProfile = function () {
                var defer = $q.defer();
                $http.post('/api/user/active-profile').then(function () {
                    Object.profile = 1;
                    defer.resolve();
                });
                return defer.promise;
            };
            Object.deactivateProfile = function () {
                var defer = $q.defer();
                $http.post('/api/user/deactivate-profile').then(function () {
                    Object.profile = 0;
                    defer.resolve();
                });
                return defer.promise;
            };

            Object.getProfile = function () {
                var defer = $q.defer();
                $http.get('/api/user/profile').then(function (response) {
                    defer.resolve(response.data);
                });
                return defer.promise;
            };

            Object.updateProfile = function (data) {
                var defer = $q.defer();
                $http.post('/api/user/profile',data).then(function (response) {
                    console.log(response.data.updated_at)
                    Object.updated_at = response.data.updated_at
                    defer.resolve(response.data);
                });
                return defer.promise;
            };

            Object.activate = function () {
                var defer = $q.defer();
                $http.post('/api/user/' + Object.id + '/set-active-status').then(function () {
                    Object.status = 'active';
                    defer.resolve();
                });
                return defer.promise;
            };
            Object.blocked = function () {
                var defer = $q.defer();
                $http.post('/api/user/' + Object.id + '/set-blocked-status').then(function () {
                    Object.status = 'blocked';
                });
                return defer.promise;
            };
            Object.update = function () {
                var defer = $q.defer();
                $http.post('/api/user/' + Object.id , Object).then(function (response) {
                    if (response.data.success == false) {
                        defer.reject(response.data.error)
                    } else {
                        defer.resolve(response.data);
                    }
                }, function (response) {
                    defer.reject(response.status + ': ' + response.statusText);
                });
                return defer.promise;
            };
            Object.updateAdministrator = function () {
                var defer = $q.defer();
                $http.post('/api/user/' + Object.id + '/administrator', Object).then(function (response) {
                    if (response.data.success == false) {
                        defer.reject(response.data.error)
                    } else {
                        defer.resolve(response.data);
                    }
                }, function (response) {
                    defer.reject(response.status + ': ' + response.statusText);
                });
                return defer.promise;
            };
            Object.deleteAdministrator = function () {
                var defer = $q.defer();
                $http.delete('/api/user/' + Object.id + '/administrator').then(function (response) {
                    if (response.data.success == false) {
                        defer.reject(response.data.error)
                    } else {
                        defer.resolve(response.data);
                    }
                }, function (response) {
                    defer.reject(response.status + ': ' + response.statusText);
                });
                return defer.promise;
            };
            Object.delete = function () {
                return $http.delete('/api/user/' + Object.id)
            };

            Object.getAdvs = function () {
                return advFactory.getByUser(Object.id)
            };
            Object.getAdvsByCurrentUser = function () {
                return advFactory.getByCurrentUser()
            };
            Object.getMyWatchAdvs = advFactory.getWatchByCurrentUser;
            Object.getEventsLog = function () {
                return $http.get('/api/user/' + Object.id + '/events-log')
            };
            Object.hasPermission = function (permission) {
                return Object.permissions.indexOf(permission) != -1
            };
            Object.sendMessage4Administrator = function (message) {
                return $http.post('/api/user/send-message-for-administrator',{message: message})
            };
            return Object;
        }
    }
})(angular, window);


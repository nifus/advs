(function (angular, window) {
    'use strict';
    angular.module('core').service('userService', userService);
    userService.$inject = ['$http' ,'advFactory'];

    function userService($http, advFactory) {
        return function (data) {
            var Object = data;
            Object.waiting = false;



            Object.sBusinessAccount = function(){
                if (Object.group_id==3){
                    return true;
                }
                return false;
            };


            Object.isPrivateAccount = function(){
                if (Object.group_id==2){
                    return true;
                }
                return false;
            };


            Object.getAdvStat = function(){
                return $http.get('/api/user/adv-stat').then( function(response){
                    return response.data;
                })
            };

            Object.changeEmail = function(data){
                return $http.put('/api/user/change-email',data)
            };
            Object.sendConfirmCode = function(email){
                return $http.post('/api/user/send-confirm-code',{email:email})
            };

            Object.changePassword = function(data){
                return $http.put('/api/user/change-password',data)
            };
            Object.changeAllowNotifications = function(data){
                return $http.put('/api/user/allow-notifications',{allow_notifications:data})
            };
            Object.deleteAccount = function () {
                return $http.delete('/api/user')
            };
            Object.changePayment = function (data) {
                return $http.put('/api/user/change-payment',data)
            };
            Object.changeContactData = function (data) {
                return $http.put('/api/user/change-contact-data',data)
            };

            Object.getAdvs = advFactory.getByUser;
            Object.getMyWatchAdvs = advFactory.getWatchByUser;

            return Object;
        }
    }
})(angular, window);


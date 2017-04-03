(function (angular, window) {
    'use strict';
    angular.module('core').service('advService', ['$http', '$q', '$cookies', '$filter', advService]);

    function advService($http, $q, $cookies, $filter) {
        var advs = $cookies.get('fav-advs');
        if (advs != undefined) {
            advs = JSON.parse(advs);
        }
        return function (data) {
            var Object = data;
            Object.waiting = false;
            Object.CreateDate = moment(data.created_at).format('DD.MM.Y');
            Object.CreateDateWithTime = moment(data.created_at).format('DD.MM.Y H:m');
            Object.DisableDateWithTime = data.disable_date ? moment(data.disable_date).format('DD.MM.Y H:m') : '-';
            Object.DisableDate = data.disable_date ? moment(data.disable_date).format('DD.MM.Y') : '-';
            Object.DeleteDate = data.disable_date ? moment(data.disable_date).format('DD.MM.Y') : '-';
            Object.EndDate = moment(data.created_at).format('DD.MM.Y');

            Object.DeleteDate = (data.disable_date) ? moment(data.disable_date).add(14, 'days').format('DD.MM.Y') : '-';
            Object.DeleteDateWithTime = (data.disable_date) ? moment(data.disable_date).add(14, 'days').format('DD.MM.Y H:m') : '-';


            Object.MainPhoto = getMainPhoto(data.photos);
            Object.IsFav = angular.isArray(advs) && advs.indexOf(data.id) !== -1 ? true : data.IsFav;
            Object.StatusMessage = function () {
                if (Object.status == 'blocked') {
                    return $filter('translate')('This advert is BLOCKED. Please react on this advert otherwise it will be automatically deleted.');
                } else if (Object.status == 'active') {
                    return $filter('translate')('This advert is active. It can be found and watched by everyone.');
                } else if (Object.status == 'payment_waiting') {
                    return $filter('translate')('This advert is active. It can be found and watched by everyone.');
                } else if (Object.status == 'disabled') {
                    return $filter('translate')('You have disabled this advert. It can NOT be found and watched by everyone.');
                } else if (Object.status == 'expired') {
                    return $filter('translate')('This advert is expired. If you don‘t reactivate it, it will be automatically deleted.');
                }
            }();



            Object.isFav = function (user) {
                if (user == null) {
                    var advs = $cookies.get('fav-advs');
                    if (advs == undefined) {
                        return false;
                    }
                    advs = JSON.parse(advs);

                    if (advs.indexOf(Object.id) !== -1) {
                        return true;
                    }
                    return false;
                } else {
                    return Object.IsFav;
                }
            };

            Object.addToFavList = function (user) {
                if (user == null) {
                    var advs = $cookies.getObject('fav-advs');
                    if (advs != undefined) {
                        advs.push(Object.id)
                    } else {
                        advs = [Object.id];
                    }
                    var expireDate = new Date();
                    expireDate.setDate(expireDate.getDate() + 199);
                    $cookies.putObject('fav-advs', advs, {expires: expireDate});
                } else {
                    $http.post('/api/adv/' + Object.id + '/fav', {'action': 'add'});
                }
                Object.IsFav = true;
            };

            Object.deleteFromFavList = function (user) {
                if (user == null) {
                    var advs = $cookies.getObject('fav-advs');
                    if (advs != undefined) {
                        var index = advs.indexOf(Object.id);
                        advs.splice(index, 1);
                        var expireDate = new Date();
                        expireDate.setDate(expireDate.getDate() + 199);
                        $cookies.putObject('fav-advs', advs, {expires: expireDate});
                    }
                } else {
                    $http.post('/api/adv/' + Object.id + '/fav', {'action': 'delete'});
                }
                Object.IsFav = false;
            };

            Object.deleteFromWatchList = function () {
                Object.waiting = true;
                return $http.delete('/api/user/watch-advs/' + Object.id).then(function (response) {
                    Object.waiting = false;
                    return response.data;
                })
            };

            Object.delete = function () {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.delete('/api/adv/' + Object.id).then(function (response) {
                    Object.waiting = false;
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };

            Object.update = function (data) {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.post('/api/adv/' + Object.id, data).then(function (response) {
                    Object.waiting = false;
                    for (var i in response.data) {
                        Object[i] = response.data[i]
                    }
                    deferred.resolve();
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };

            Object.disable = function () {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.put('/api/user/advs/' + Object.id + '/status', {status: 'disabled'}).then(function (response) {
                    Object.waiting = false;
                    Object.status = 'disabled';
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };

            Object.block = function () {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.put('/api/user/advs/' + Object.id + '/status', {status: 'block'}).then(function (response) {
                    Object.waiting = false;
                    Object.status = 'block';
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };

            Object.activate = function () {
                var deferred = $q.defer();
                Object.waiting = true;
                $http.put('/api/user/advs/' + Object.id + '/status', {status: 'active'}).then(function (response) {
                    Object.waiting = false;
                    Object.status = 'active';
                    deferred.resolve(response);
                }, function (error) {
                    deferred.reject(error.data);
                    console.log(error);
                });
                return deferred.promise;
            };

            Object.sendMessage = function (data) {
                var deferred = $q.defer();
                $http.post('/api/adv/' + Object.id + '/message', data).then(function (response) {
                    deferred.resolve(response.data);
                }, function (error) {
                    deferred.reject(error.data);
                });
                return deferred.promise;
            };


            return (Object);
        };
        function getMainPhoto(photos) {
            if (photos) {
                return photos[0];
            }
            return null;
        }

    }
})
(angular, window);


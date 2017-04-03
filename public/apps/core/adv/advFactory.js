(function (angular) {

    'use strict';

    angular.module('core')
        .factory('advFactory', ['advService', '$http', '$q', '$filter', advFactory]);

    function advFactory(advService, $http, $q, $filter) {
        var categories = [
            { 'id':1, 'title':'Flat', 'is_sale_only':false, 'ic_business':false},
            { 'id':2, 'title':'House', 'is_sale_only':false, 'ic_business':false},
            { 'id':3, 'title':'Garage / car space', 'is_sale_only':false, 'ic_business':false},
            { 'id':5, 'title':'Building ground', 'is_sale_only':true, 'ic_business':false },
            { 'id':4, 'title':'Office / Praxis', 'is_sale_only':false, 'ic_business':true},
            { 'id':6, 'title':'Gastronomy / Hotel', 'is_sale_only':false, 'ic_business':true},
            { 'id':7, 'title':'Hall / Production / Warehouse', 'is_sale_only':false, 'ic_business':true},
            { 'id':8, 'title':'Retail trade', 'is_sale_only':false, 'ic_business':true},
            { 'id':9, 'title':'Commercial land', 'is_sale_only':false, 'ic_business':true}
        ];
        return {
            energy_source: [
                {id: 'Geothermal energy', value: $filter('translate')('Geothermal energy')},
                {id: 'Solar', value: $filter('translate')('Solar')},
                {id: 'Wood', value: $filter('translate')('Wood')},
                {id: 'Gas', value: $filter('translate')('Gas')},
                {id: 'Oil', value: $filter('translate')('Oil')},
                {id: 'Teleheating', value: $filter('translate')('Teleheating')},
                {id: 'Electricity', value: $filter('translate')('Electricity')},
                {id: 'Coal', value: $filter('translate')('Coal')},
                {id: 'Other', value: $filter('translate')('Other')}
            ],
            heating: [
                {id: 'Self-contained central heating', value: $filter('translate')('Self-contained central heating')},
                {id: 'Centralheating', value: $filter('translate')('Centralheating')},
                {id: 'Teleheating', value: $filter('translate')('Teleheating')},
                {id: 'Other', value: $filter('translate')('Other')}
            ],
            energy_class: [
                {id: 'Any', value: $filter('translate')('Any')},
                {id: 'A+', value: $filter('translate')('A+')},
                {id: 'A', value: $filter('translate')('A')},
                {id: 'B', value: $filter('translate')('B')},
                {id: 'C', value: $filter('translate')('C')},
                {id: 'D', value: $filter('translate')('D')},
                {id: 'E', value: $filter('translate')('E')},
                {id: 'F', value: $filter('translate')('F')},
                {id: 'G', value: $filter('translate')('G')},
                {id: 'H', value: $filter('translate')('H')}
            ],
            categories:categories,
            store: store,
           // getUserAdvById: getUserAdvById,
            getByUser: getByUser,
            getByCurrentUser: getByCurrentUser,
            getById: getById,
            getWatchByCurrentUser: getWatchByCurrentUser,
            getDataSets: getDataSets,
           // getResult: getResult,
            getStatistics: getStatistics,
            restoreAdvert: restoreAdvert,
            guid: guid,
            getCategoryName: getCategoryName,
        };


        function getStatistics() {
            var defer = $q.defer();
            $http.get('/api/adv/statistics').then(function (response) {
                defer.resolve(response.data);
            });
            return defer.promise;
        }




        function getDataSets() {
            var deferred = $q.defer();
            $http.get('/api/adv-data-sets').then(function (response) {
                deferred.resolve(response.data);
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function store(data) {
            var deferred = $q.defer();
            $http.post('/api/adv', data).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        /*function getUserAdvById(id) {
            var deferred = $q.defer();
            $http.get('/api/adv/by-user/' + id).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                console.log(error);
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }*/

        function getById(id) {
            var deferred = $q.defer();
            $http.get('/api/adv/' + id).then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function restoreAdvert(id) {
            var deferred = $q.defer();
            $http.get('/api/adv/' + id+'/restore').then(function (response) {
                deferred.resolve(new advService(response.data));
            }, function (error) {
                deferred.reject({success: false, error: error.data});
            });
            return deferred.promise;
        }

        function getByUser(user_id) {
            return $http.get('/api/adv/by-user/'+user_id).then(function (response) {
                var objs = [];
                for (var i in response.data) {
                    objs.push(new advService(response.data[i]))
                }
                return objs;
            })
        }
        function getByCurrentUser() {
            return $http.get('/api/adv/by-current-user').then(function (response) {
                var objs = [];
                for (var i in response.data) {
                    objs.push(new advService(response.data[i]))
                }
                return objs;
            })
        }

        function getWatchByCurrentUser() {
            return $http.get('/api/adv/watch/by-current-user').then(function (response) {
                var objs = [];
                for (var i in response.data) {
                    objs.push(new advService(response.data[i]))
                }
                return objs;
            })
        }
        function guid() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                return v.toString(16);
            });
        }

        function getCategoryName(id) {
            for (var i in categories) {
                if (categories[i].id == id) {
                    return categories[i].title
                }
            }
        }

    }


})(angular);




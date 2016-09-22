(function (angular, window) {

    'use strict';

    angular.module('core')
        .factory('newsFactory', newsFactory);
    newsFactory.$inject = ['newsService', '$http'];

    function newsFactory(newsService, $http) {

        return {
            getLastPrivateNews: getLastPrivateNews,
            getLastBusinessNews: getLastBusinessNews,
            getLastNews: getLastNews

        };

        function getLastPrivateNews() {
            return $http.get( '/api/news/private').then(function (response) {
                var news = [];
                for( var i in response.data ){
                    news.push( new newsService(response.data[i]) )
                }
                return news;
            })
        }

        function getLastBusinessNews() {
            return $http.get( '/api/news/business').then(function (response) {
                var news = [];
                for( var i in response.data ){
                    news.push( new newsService(response.data[i]) )
                }
                return news;
            })
        }

        function getLastNews() {
            return $http.get( '/api/news/all').then(function (response) {
                var news = [];
                for( var i in response.data ){
                    news.push( new newsService(response.data[i]) )
                }
                return news;
            })
        }

    }


})(angular, window);




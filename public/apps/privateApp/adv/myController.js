(function () {
    'use strict';
    angular.module('privateApp').controller('myController', myController);

    myController.$inject = ['$scope', 'userFactory', 'newsFactory','$q'];

    function myController($scope, userFactory, newsFactory,$q) {
        $scope.user = null;
        $scope.promises = null;
        $scope.env = {
            loading: true,
            news: [],
            stat:{
                rent: {
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                },
                sell:{
                    total: 0,
                    payment_waiting: 0,
                    active: 0,
                    disabled: 0,
                    expired: 0,
                    blocked: 0
                }
            }
        };

        function initPage(deferred) {
            $scope.user = $scope.$parent.env.user;

            var newsPromise = null;
            if ($scope.user.isPrivateAccount()){
                newsPromise = newsFactory.getLastPrivateNews();
            }else{
                newsPromise = newsFactory.getLastBusinessNews();
            }

            newsPromise.then(function (news) {
                $scope.env.news = news;
            });

            var statPromise = $scope.user.getAdvStat().then( function(result){
                $scope.env.stat = result;
            });

            $q.all([statPromise,newsPromise]).then(function(){
               $scope.env.loading = false;
            })
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);


        $scope.logout = function () {
            userFactory.logout();
            window.location.reload(true)
        };


    }
})();


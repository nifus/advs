(function () {
    'use strict';
    angular.module('privateApp').controller('editController', editController);

    editController.$inject = ['$scope', 'advFactory', '$q', '$filter','$state'];

    function editController($scope, advFactory,  $q, $filter, $state) {
       // $scope.user = null;
        $scope.model = {};
        $scope.adv = {};
        var promises = [];

        $scope.env = {
            id: $state.params.id,
            loading: true,
            subcats:[],
            equipments: [],
            categories: [],
        };

        function initPage(deferred) {

            var advPromise = advFactory.getUserAdvById($scope.env.id).then(function(adv){
                $scope.model = adv;
                $scope.adv = adv;

                console.log(adv)
            });
            promises.push(advPromise);

            var dataSetPromise = advFactory.getDataSets().then(function (response) {
                $scope.env.subcats = response.sub_categories;
                $scope.env.equipments = response.equipments;
                $scope.env.categories = response.categories;
            });
            promises.push(dataSetPromise);


            $q.all(promises).then(function () {
                $scope.env.loading = false;
                $scope.env.category_name = getCategoryName($scope.adv.category, $scope.env.categories);
            });
            return deferred.promise;
        }

        // initPage();
        $scope.$parent.init.push(initPage);



        function getCategoryName(id, cats) {
            for( var i in cats ){
                if( cats[i].id==id ){
                    return cats[i].title
                }
            }
        }




    }
})();


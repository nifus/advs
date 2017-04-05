(function (angular) {
    'use strict';


    function uploadDirective($compile, $interval) {
        return {
            ngModel: 'require',
            replace: true,
            restrict: 'E',
            link: uploadLink,
            controller: uploadController,
            templateUrl: '/apps/directives/upload/upload.html',

            scope: {
                ngModel: '=',
                numberOfFiles: '@',
                ngChange: '=',
            }
        };


        function uploadLink(scope, element, el2) {

            element.find('#upload_trigger').click(function () {
                element.find('input[type=file]').trigger('click')
            })
        }

       // uploadController.$inject = ['$scope'];

        function uploadController($scope) {

           // $scope.allowAddNewFiles = false;
            //$scope.count = 0;
           // var max = $scope.numberOfFiles == undefined || $scope.numberOfFiles <= 1 ? 1 : $scope.numberOfFiles;
            $scope.$watch('ngModel', function (value) {
               /* if (angular.isString(value)) {
                    $scope.ngModel = [value]
                }else if( angular.isArray(value) ) {
                    $scope.ngModel = value
                }
                rematch(value)*/
            }, true);

            $scope.$watch('file', function (value) {
console.log(value)
                /*if (value == null) {
                    return false;
                }
                if (!$scope.ngModel) {
                    $scope.ngModel = max == 1 ? [value] : [];
                }

                if (angular.isArray($scope.ngModel) && angular.isArray(value)) {
                    for (var i in value) {
                        if ($scope.ngModel.length < max){
                            if ( value[i].filetype.indexOf('image')!==-1){
                                $scope.ngModel.push(value[i])
                            }else{
                                alertify.error( 'File '+value[i].filename+' is not an image' );
                            }
                        }

                    }
                } else if (angular.isArray($scope.ngModel) && angular.isObject(value)) {
                   // if ($scope.ngModel.length < max)
                       // $scope.ngModel.push(value)

                }
                if ($scope.ngChange) {
                    $scope.ngChange($scope.ngModel);
                }*/

            }, true);


            $scope.deleteItem = function (index) {
                if (angular.isArray($scope.ngModel)) {
                    $scope.ngModel.splice(index, 1);
                }

                rematch($scope.ngModel);
                if ($scope.ngChange) {
                    $scope.ngChange($scope.ngModel);
                }

            };

            function rematch(value) {
                if ($scope.hideResult) {
                    return false;
                }
                var allowAddNewFiles = ( max == 1 && value != undefined && value.length == 1 ) ? false : true;
                allowAddNewFiles = ( max > 1 && angular.isArray($scope.ngModel) && $scope.ngModel.length < max ) ? true : allowAddNewFiles;

                if (allowAddNewFiles == false) {
                    $scope.button.css('display', 'none')
                } else {
                    $scope.button.css('display', 'block')
                }
            }
        }


    }

    angular.module('core').directive('upload', uploadDirective);


})(window.angular);

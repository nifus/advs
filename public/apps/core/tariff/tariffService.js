(function (angular, window) {
    'use strict';
    angular.module('core').service('tariffService', tariffService);
    tariffService.$inject = ['$http', '$q', '$cookies'];

    function tariffService($http, $q, $cookies) {

        return function (tariff, tariffDetails, tariffs) {
            var Object = tariff;
            Object.tariffs = tariffs;
            Object.details = tariffDetails;
            Object.StartDate = moment(Object.start_time).format('DD.MM.YYYY HH:mm');
            Object.EndDate = moment(Object.end_time).format('DD.MM.YYYY HH:mm');
            Object.EndDate = moment(Object.end_time).format('DD.MM.YYYY HH:mm');
            Object.Range = moment(Object.end_time).diff( moment(Object.start_time), 'days');
            Object.TariffName =  tariffs.filter( function (tariff) {
                if ( tariff.id==Object.tariff_id  ){
                    return true;
                }
                return false;
            })[0].name;
            Object.AdditionalSlots =  calcAdditionalSlots(Object.details);
            Object.UsedSlots =  calcUsedSlots(Object.details);
            Object.FreeSlots =  Object.slots - Object.UsedSlots;
            Object.UsedAdditionalSlots =  calcUsedAdditionalSlots(Object.details);
            Object.FreeAdditionalSlots =  Object.AdditionalSlots - Object.UsedAdditionalSlots;




            return (Object);

            function calcAdditionalSlots(slots) {
                var count = 0;
                for( var i in slots ){
                    if(slots[i].is_extra_slot=='1'){
                        count+=1;
                    }
                }
                return count;
            }
            function calcUsedAdditionalSlots(slots) {
                var count = 0;
                for( var i in slots ){
                    if(slots[i].is_extra_slot=='1' && slots[i].adv!=null){
                        count+=1;
                    }
                }
                return count;
            }
            function calcUsedSlots(slots) {
                var count = 0;
                for( var i in slots ){
                    if(slots[i].is_extra_slot=='0' && slots[i].adv!=null){
                        count+=1;
                    }
                }
                return count;
            }
        };
    }
})(angular, window);


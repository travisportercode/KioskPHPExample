/** 
 * Itinerary Service
 */

App.factory('Itinerary', ['$rootScope', '$filter', '$log', 'filterFilter', 'Utils', 'TicketTypes', function ($rootScope, $filter, $log, filterFilter, Utils, TicketTypes) {

    Itinerary = {};

    // Update itinerary handler
    Itinerary.update = function (exhibition, presentation, exhibitions, singlePresentation) {

        // Itinerary obj model
        var itineraryItem = {
            id: presentation.performanceId || presentation.defaultPresentationId,
            zoneId: presentation.zoneId ? parseInt(presentation.zoneId) : 0,
            exhibitionId: exhibition.id,
            name: exhibition.name || presentation.name || null,
            durationMinutes: exhibition.durationMinutes,
            forExhibitions: presentation.forExhibitions || false
        };

        // Remove duplucate presentation
        angular.forEach($rootScope.$storage.itinerary, function (item, key) {
            if (item.id === itineraryItem.id && item.zoneId === itineraryItem.zoneId) $rootScope.$storage.itinerary.splice(key, 1);
        });

        // Single item itinerary
        if (singlePresentation) {
            $rootScope.$storage.itinerary = [];
            angular.forEach(exhibitions, function (exh) {
                if (!angular.equals(exhibition, exh)) {
                    angular.forEach(exh.presentations, function (pre, key) {
                        pre.selected = false;
                    });
                    // Upate exhibition presentation to itself so the select box can reset selection
                    exh.presentations = angular.copy(exh.presentations);
                }
            });
        }

        // Add presentation to itinerary
        if (presentation.selected) $rootScope.$storage.itinerary.push(angular.copy(itineraryItem));

    };

    //Id in itinerary
    Itinerary.hasPresentation = function (obj) {
        var ret = false;
        angular.forEach($rootScope.$storage.itinerary, function (item) {
            if (item.id === obj.performanceId && item.zoneId === obj.zoneId) ret = true;
        });
        return ret;
    };

    //Remove item from itinerary
    Itinerary.removeItem = function (item) {
        if (!item || !angular.isObject(item)) return $log.error('id passed to Itinerary.removeItem is invalid.');
        $rootScope.$storage.itinerary = $rootScope.$storage.itinerary.filter(function (currentItem) {
            return item.performanceId != currentItem.id;
        });
    };

    //Remove items from itinerary
    Itinerary.removeItems = function (items) {
        if (!angular.isArray(items)) return $log.error('ids passed to Itinerary.removeItems is invalid');
        angular.forEach(items, function (item, key) {
            Itinerary.removeItem(item);
        });
    };

    return Itinerary;

}]);
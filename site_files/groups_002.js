/** 
 * Tickets Service
 */

App.factory('Groups', ['$rootScope', '$window', '$log', '$timeout', '$filter', 'filterFilter', 'toaster', 'Utils', 'Constants', 'Storage', 'GroupsPresentationsBatch', 'GroupsTransportation', 'GroupsHallsOfFocus', 'GroupsExhibitionPresentations', 'Presentation',
    function ($rootScope, $window, $log, $timeout, $filter, filterFilter, toaster, Utils, Constants, Storage, GroupsPresentationsBatch, GroupsTransportation, GroupsHallsOfFocus, GroupsExhibitionPresentations, Presentation) {

    var Groups = {};

    /**
    * Get 'presentations' lunchrooms, arrival times and departure times
    **/
    Groups.getPresentationsBatch = function (id, spinner, callback) {
        if (spinner) $rootScope.$storage.spinner = true;
        GroupsPresentationsBatch.get({ presentationid: id }, function (response) {
            if (spinner) $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            else return response;
        }, function (response) {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);
            $log.error('getPresentationsBatch get request failed: ', response);
            if (spinner) $rootScope.$storage.spinner = false;
        });
    };
    
    /**
    * Get transportation data
    **/
    Groups.getTransportation = function (date, callback) {
        $rootScope.$storage.spinner = true;
        GroupsTransportation.get({ date: $filter('date')(date, 'shortDate') }, function (response) {
            $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            else return response;
        }, function (response) {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            $log.error('GroupsTransportation get request failed: ', response);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Get transportation data
    **/
    Groups.getExhibitionPresentations = function (date, spinner, callback) {
        if (spinner) $rootScope.$storage.spinner = true;
        GroupsExhibitionPresentations.query({ date: $filter('date')(date, 'shortDate') }, function (response) {
            if (spinner) $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            else return response;
        }, function (response) {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            $log.error('GroupsExhibitionPresentations get request failed: ', response);
            if (spinner) $rootScope.$storage.spinner = false;
        });
    };

    //Wrap presentation object with exhibition
    Groups.wrapWithExhibition = function (obj) {
        if (!angular.isObject(obj)) return $log.error('obj passed to wrapWithExhibition is invalid.');
        var newObj = {};
        var oldObj = angular.copy(obj);
        //Extend old object into new object
        newObj = angular.extend(newObj, {
            id: oldObj[0].exhibitionId,
            name: oldObj[0].exhibitionName,
            presentations: oldObj
        }, oldObj[0]);
        //Return new object
        return newObj;
    };

    Groups.getPresentations = function(id, callback) {
        $rootScope.$storage.spinner = true;
        Presentation.query({ defaultPresentationId: id }, function(presentations) {
            $rootScope.$storage.spinner = false;
            if (angular.isFunction(callback)) callback(presentations);
            else return presentations;
        }, function(error) {
            // Apply callback if provided
            if (angular.isFunction(response)) callback([]);
            $rootScope.$storage.spinner = false;
        });
    };

    Groups.getLunchrooms = function (date, callback) {

        $rootScope.$storage.spinner = true;

        GroupsLunchrooms.query({ date: $filter('date')(date, 'shortDate') }, function (lunchrooms) {
            $rootScope.$storage.spinner = false;
            // Update ticket data
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(lunchrooms);
            else return lunchrooms;    
        }, function (response) {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            $rootScope.$storage.spinner = false;
        });
    };


    /**
    * Get Halls of Focus data
    **/
    Groups.getHallsOfFocus = function (date, callback) {
        $rootScope.$storage.spinner = true;
        GroupsHallsOfFocus.query({ date: $filter('date')(date, 'shortDate') }, function (halls) {
            $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(halls);
            else return halls;
        }, function (response) {
            // Apply callback if provided
            if (angular.isFunction(response)) callback([]);
            $log.error('GroupsHallsOfFocus get request failed: ', response);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Get array of bus field values
    **/
    Groups.getBusOptionValues = function (maxBuses) {
        var buses = [];
        for (var i = 0; i < maxBuses; i++) {
            buses.push((i + 1).toString());
        }
        if (buses.length < 1) return $log.error('buses array empty in Groups.getBusFieldValues');
        return buses;
    };

    /**
    * Validate adult to child ratio
    **/
    Groups.isValidAdultRatio = function () {

        if (!angular.isObject($rootScope.$storage.selectedTicketType)) return false;
        if ($rootScope.$storage.selectedTicketType && !$rootScope.$storage.selectedTicketType.priceTypes) return false;

        var priceTypes = angular.copy($rootScope.$storage.selectedTicketType.priceTypes);
        var adults = 0;
        var children = 0;

        if (!angular.isObject(priceTypes)) return $log.error('priceTypes invalid in isValidAdultRatio');

        angular.forEach(priceTypes, function (priceType) {

            angular.forEach(Constants.groups.priceTypeGroups.adults, function (adultGroup) {
                if (priceType.priceTypeGroup == adultGroup) adults += priceType.quantity;
            });

            angular.forEach(Constants.groups.priceTypeGroups.children, function (childGroup) {
                if (priceType.priceTypeGroup == childGroup) children += priceType.quantity;
            });

        });

        if ((adults / children) >= Constants.groups.adultChildRatio) return true;
        else return false;
    };

    /**
    * Reset a step by providing a key string of the groups object to reset
    **/
    Groups.resetStep = function (step, callback) {
        if (angular.isUndefined(step) || typeof step !== 'string') return $log.error('step argument provided to Groups.resetStep is invalid');
        if (!angular.isObject($rootScope.$storage.groups[step])) return $log.error('$rootScope.$storage.groups[' + step + '] is invalid in $rootScope.$storage.groups');
        if (!angular.isObject(Storage.defaults.session.groups[step])) return $log.error('Storage.defaults.session.groups[' + step + '] is invalid in $rootScope.$storage.groups');
        $rootScope.$storage.groups[step] = angular.copy(Storage.defaults.session.groups[step]);
        if (angular.isFunction(callback)) callback();
    };

    /**
    * Reset multiple steps at a time by providing an array of key strings of the groups object to reset
    **/
    Groups.resetSteps = function (steps, callback) {
        if (angular.isUndefined(steps) || !angular.isObject(steps) || steps.length < 1) return $log.error('steps argument provided to Groups.resetSteps is invalid');
        for (var i = 0; i < steps.length; i++) {
            Groups.resetStep(steps[i]);
        }
        if (angular.isFunction(callback)) callback();
    };

    return Groups;

}]);
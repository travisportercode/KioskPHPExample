/** 
 * Presentations Service
 */

App.factory('Presentations', ['$rootScope', '$filter', 'filterFilter', 'Utils', 'TicketTypes', function ($rootScope, $filter, filterFilter, Utils, TicketTypes) {

    var Presentations = {};

    /**
    * Save in storage
    */
    Presentations.store = function (presentations) {
        if (presentations) $rootScope.$storage.selectedTicketType.presentations = presentations;
    };

    /**
    * Get ticket type details with price types and presentations (tickets in advanced)
    */
    Presentations.get = function (date, category, callback) {

        $rootScope.$storage.spinner = true;

        // Get new ticket
        TicketTypes.get({ date: $filter('date')(date, 'shortDate'), category: category }, function (ticket) {

            $rootScope.$storage.spinner = false;
            $rootScope.$storage.selectedTicketType = angular.extend({}, $rootScope.$storage.selectedTicketType, ticket);

            // Update ticket data
            Presentations.updateTicket(ticket);

            // Apply callback if provided
            if (angular.isFunction(callback)) callback(ticket.presentations);
            else return ticket.presentations;            

        }, function () {

            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);

            $rootScope.$storage.spinner = false;

        });
    };

    /**
    * Get selected presentations
    */
    Presentations.getSelected = function (returnObj) {
        var presentations = {};
        angular.forEach($rootScope.$storage.itinerary, function (presentation) {
            presentations[presentation.id] = presentation.zoneId || 0;
        });
        if (returnObj) return presentations;
        else return {
            selectedPresentations: presentations
        };
    };

    /**
    * Attach - attach presentations to exhibitions
    */
    Presentations.attach = function (presentations, exhibitions, callback) {

        if (!presentations || !exhibitions) return;

        // Attach presentation to their exhibition based on id
        if (exhibitions) {
            angular.forEach(exhibitions, function (exhibition) {
                exhibition.presentations = angular.copy(presentations).filter(function (presentation) {
                    return presentation.exhibitionId === exhibition.id;
                });
            });
        }

        // Apply callback if provided
        if (angular.isFunction(callback)) callback(exhibitions);

    };

    /**
    * Unselect - Add selected property to each presentation
    */
    Presentations.unselect = function (presentations) {

        if (!presentations) return;

        // Add selected property to each presentation
        angular.forEach(presentations, function (presentation) {
            presentation.selected = false;
        });
       
    };

    /**
    * Update Ticket - update ticket ids from selected ticket
    */
    Presentations.updateTicket = function (ticket) {

        if (!ticket) return;

        // Update itemTypeid
        $rootScope.$storage.selectedTicketType.id = ticket.id;

        // Update IDs from price types
        angular.forEach(ticket.priceTypes, function (priceType, key) {
            $rootScope.$storage.selectedTicketType.priceTypes[key].id = priceType.id;
        });

    };

    /**
    * Clear itinerary
    */
    Presentations.clearSelected = function () {
        // Clear itinerary from previous session
        $rootScope.$storage.itinerary.length = 0;
    };
    
    return Presentations;

}]);
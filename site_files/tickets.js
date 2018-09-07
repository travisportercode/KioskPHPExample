/** 
 * Tickets Service
 */

App.factory('Tickets', ['$rootScope', '$window', '$timeout', '$filter', 'filterFilter', 'toaster', 'Utils', 'TicketTypes', 'TicketDetails', 'TicketUpgradeTypes', 'TicketEdit', 'TicketElements', 'TicketPrint', 'TicketRecommendations', function ($rootScope, $window, $timeout, $filter, filterFilter, toaster, Utils, TicketTypes, TicketDetails, TicketUpgradeTypes, TicketEdit, TicketElements, TicketPrint, TicketRecommendations) {

    var Tickets = {};

    /**
    * Get full ticket type details with price types and presentations (tickets in advanced)
    */
    Tickets.get = function (date, category, callback) {
        $rootScope.$storage.spinner = true;
        TicketTypes.get({ date: $filter('date')(date, 'shortDate'), category: category }, function (ticket) {
            $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(ticket);
            else return ticket;
        }, function () {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Get ticket types
    */
    Tickets.getTypes = function (callback) {
        $rootScope.$storage.spinner = true;
        // Get ticket types array
        TicketTypes.query(function (tickets) {
            // Set selected propery
            angular.forEach(tickets, function (ticket) {
                ticket.selected = false;
            });
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(tickets);
            $rootScope.$storage.spinner = false;
        }, function () {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);
            toaster.pop({
                type: 'error',
                body: 'Ticket types not found. Please try again.'
            });
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Get ticket details with price types and presentations (tickets in advanced)
    */
    Tickets.getDetails = function (ticketId, callback) {
        if (!ticketId) return;
        $rootScope.$storage.spinner = true;
        // Get ticket type with price types
        TicketDetails.get({ ticketId: ticketId }, function (ticket) {
            // Update stored ticket
            $rootScope.$storage.selectedTicketType = ticket;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(ticket);
            $rootScope.$storage.spinner = false;
        }, function () {
            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);
            toaster.pop({
                type: 'error',
                body: 'Ticket types not found. Please try again.'
            });
            $rootScope.$storage.spinner = false;
        });
    };

    /**
     * Recommend - recommends a ticket (logic comes from the back-end) [NBU - not being used]
     */
    Tickets.recommend = function (ticketPrice) {
        $rootScope.$storage.spinner = true;
        TicketRecommendations.get({ numberOfPresentations: $rootScope.$storage.itinerary.length }, function (ticketType) {
            // Store ticket type
            $rootScope.$storage.selectedTicketType = ticketType;
            // Select ticket type
            if ($rootScope.$storage.selectedTicketType) $rootScope.$storage.selectedTicketType.selected = true;
            // Override price functionality from args
            if (ticketPrice) $rootScope.$storage.selectedTicketType.price = ticketPrice;
            // Override price from single event
            if ($rootScope.$persistentStorage.eventMode && $rootScope.$persistentStorage.event.price) $rootScope.$storage.selectedTicketType.price = $rootScope.$persistentStorage.event.price;
            $rootScope.$storage.spinner = false;
        }, function () {
            $rootScope.$storage.spinner = false;
        });
    };

    /**
     * Get Selected - get selected price types from selected ticket type
     */
    Tickets.getSelected = function (returnObj) {
        var priceTypes = {};
        if ($rootScope.$storage.selectedTicketType.selectedPriceTypes) {
            priceTypes = angular.copy($rootScope.$storage.selectedTicketType.selectedPriceTypes);
        } else {
            angular.forEach($rootScope.$storage.selectedTicketType.priceTypes, function (priceType, key) {
                if (priceType.quantity) priceTypes[priceType.id] = priceType.quantity;
            });
        }
        if (returnObj) return priceTypes;
        else return {
            selectedPriceTypes: priceTypes
        };
    };

    /**
    * Transfer quantites - update ticket quantities from selected ticket
    */
    Tickets.transferQuantites = function (cachedPriceTypes) {
        if (!cachedPriceTypes || !$rootScope.$storage.selectedTicketType) return;
        // Update quantites from price types
        angular.forEach(cachedPriceTypes, function (cachedPriceType, key) {
            $rootScope.$storage.selectedTicketType.priceTypes[key].quantity = cachedPriceType.quantity;
        });
    };

    /**
     * Print - print ticket functionality
     */
    Tickets.print = function (orderId) {
        if (!orderId) return;
        // Show spinner
        $rootScope.$storage.spinner = true;
        // Get ticket elements
        TicketElements.query({ orderId: orderId }, function (tickets) {
            if (tickets.length) {
                // Set printing flag to in progress
                $rootScope.$storage.confirmation.printing = 'in progress';
                // For each ticket
                angular.forEach(tickets, function (ticket, key) {
                    Tickets.printElement(ticket.fgl, (tickets.length === (key + 1)));
                });
            } else {
                Tickets.printError();
            }
        }, function () {
            Tickets.printError();
        });
        // Print ticket element
        Tickets.printElement = function (element, isLast) {
            TicketPrint.send('fgl=' + element, function () {
                if (isLast) {
                    // Notify user on print success
                    Tickets.printSuccess();
                    // Set printing flag to done
                    $rootScope.$storage.confirmation.printing = 'done';
                }
            }, function () {
                Tickets.printError();
            });
        };
        // Print success callback
        Tickets.printSuccess = function () {
            // Stop spinner
            $rootScope.$storage.spinner = false;
            // Notify user print was successfull
            toaster.pop({
                type: 'success',
                body: 'Your tickets have been printed!'
            });
        };
        // Print error callback
        Tickets.printError = function () {
            // Stop spinner
            $rootScope.$storage.spinner = false;
            // Notify user print failed
            toaster.pop({
                type: 'error',
                body: 'Sorry, could not print tickets at this time. '
            });
        };
    };

    /**
    * Ticket types for upgrade
    */
    Tickets.getTypesForUpgrade = function (params, callback) {
        if (!params) return;
        $rootScope.$storage.spinner = true;
        // Get ticket types for upgrade
        TicketUpgradeTypes.get(params, function (response) {
            // Update stored ticket
            $rootScope.$storage.selectedTicketType = response.ticketTypes;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            $rootScope.$storage.spinner = false;
        }, function (response) {
            // Go to error page
            Utils.goTo('/error/' + response.data.messages[0].errorCode.toLowerCase());
            // Apply callback if provided
            if (angular.isFunction(callback)) callback([]);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Ticket edit for upgrade and swap
    */
    Tickets.edit = function (params, callback) {
        if (!params) return;
        $rootScope.$storage.spinner = true;
        // Get ticket types for upgrade
        TicketEdit.get(params, function (response) {
            // Update stored ticket
            $rootScope.$storage.selectedTicketType = response.ticketType;
            $rootScope.$storage.selectedTicketType.selectedPriceTypes = response.selectedPriceTypes;
            // Apply callback if provided
            if (angular.isFunction(callback)) callback(response);
            $rootScope.$storage.spinner = false;
        }, function (response) {
            // Go to error page
            Utils.goTo('/error/' + response.data.messages[0].errorCode.toLowerCase());
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Clear selected ticket type
    */
    Tickets.clearSelected = function () {
        // Clear ticket from previous session
        $rootScope.$storage.selectedTicketType = null;
    };

    return Tickets;

}]);
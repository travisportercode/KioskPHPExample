/*************************** Ticket Upgrade Controller ***************************/

App.controller('ticketUpgradeCtrl', ['$scope', '$rootScope', '$routeParams', 'Utils', 'Tickets', 'Cart', function ($scope, $rootScope, $routeParams, Utils, Tickets, Cart) {

    // Clear itinerary from previous session
    $rootScope.$storage.itinerary.length = 0;

    // Object data for ticket types API call
    var ticketTypesPayload = {
        ticketId: $routeParams.ticketno || null,
        orderId: $routeParams.orderid || null
    };

    // Get Ticket Types array
    Tickets.getTypesForUpgrade(ticketTypesPayload, function (response) {
        $scope.ticketTypes = response.ticketTypes;
    });
    
    // Get ticket type details with price types and presentations
    $scope.selectTicketType = function (selectedTicket) {

        // Object data for ticket details API call
        var ticketEditPayload = {
            orderTicketType: selectedTicket.id || null,
            orderId: $routeParams.orderid || null,
            orderTicketTypeId: $routeParams.ordertickettype
        };

        // Get ticket details for edit
        Tickets.edit(ticketEditPayload, function (ticket) {
            // Go to Itinerary page
            Utils.goTo('/itinerary/edit');
        });
    };

}]);
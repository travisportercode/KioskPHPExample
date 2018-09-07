/*************************** Ticket Swap Controller ***************************/

App.controller('ticketSwapCtrl', ['$scope', '$rootScope', '$routeParams', 'Utils', 'Tickets', 'Cart', function ($scope, $rootScope, $routeParams, Utils, Tickets, Cart) {

    // Clear itinerary from previous session
    $rootScope.$storage.itinerary.length = 0;

    // Object data for ticket details API call
    var ticketEditPayload = {
        orderTicketType: $routeParams.ordertickettype || null,
        orderId: $routeParams.orderid || null,
        orderTicketTypeId: $routeParams.ordertickettype || null
    };
    
    // Get ticket details for edit
    Tickets.edit(ticketEditPayload, function (ticket) {
        // Go to Itinerary page
        Utils.goTo('/itinerary/edit');
    });

}]);
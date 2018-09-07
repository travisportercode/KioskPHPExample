/*************************** Ticket Types Controller ***************************/

App.controller('ticketTypesCtrl', ['$scope', '$rootScope', 'Utils', 'Tickets', function ($scope, $rootScope, Utils, Tickets) {

    // Get Ticket Types array
    Tickets.getTypes(function (tickets) {
        $scope.ticketTypes = tickets;
    });
    
    // Get ticket type details with price types and presentations
    $scope.selectTicketType = function (ticket) {
        Tickets.getDetails(ticket.id, function (ticket) {
            if (ticket.category != 'GeneralAdmission' && $rootScope.breakpoint != 'xl') Utils.goTo('/itinerary');
            else if ($rootScope.breakpoint === 'xl' && !$rootScope.$storage.user.postalCode) Utils.goTo('/user/zip');
            else Utils.goTo('/tickets/prices');
        });
    };

}]);
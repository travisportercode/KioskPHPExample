App.controller('eventCtrl', ['$scope', '$rootScope', '$routeParams', 'toaster', 'Utils', 'TicketDetails', 'User', 'filterFilter', function ($scope, $rootScope, $routeParams, toaster, Utils, TicketDetails, User, filterFilter) {

    // Check to see if to see where the eventId is coming from and setting it to $scope.eventId 
    if ($routeParams.eventId) $scope.eventId = $routeParams.eventId;
    else if ($rootScope.$persistentStorage.event.id) $scope.eventId = $rootScope.$persistentStorage.event.id;
    else return;

    // Get event ticket
    $scope.getEvent = function (callback) {
        // Query the exhibitions API and filter by only an eventType of SpecialEvent.
        $rootScope.$storage.spinner = true;
        TicketDetails.get({ ticketId: $scope.eventId, category: 'SpecialEvents' }, function (ticket) {
            $rootScope.$storage.spinner = false;
            // Update whole ticket
            $rootScope.$storage.selectedTicketType = ticket;
            // Add event to itinerary once
            if (!filterFilter($rootScope.$storage.itinerary, { id: ticket.id })[0]) $rootScope.$storage.itinerary.push({ id: ticket.id });
            // Apply callback if provided
            if (angular.isFunction(callback)) callback();
        }, function () {
            toaster.pop({
                type: 'error',
                body: 'Event not found'
            });
            $rootScope.$storage.selectedTicketType = null;
            $rootScope.$storage.spinner = false;
        });
    };

    // Only get event if available
    if (!$rootScope.$storage.selectedTicketType || $rootScope.$storage.selectedTicketType && $rootScope.$storage.selectedTicketType.id != $scope.eventId) $scope.getEvent();

    // Continue flow
    $rootScope.continue = function () {
        if (!$rootScope.$storage.selectedTicketType.isMembersOnly || ($rootScope.$storage.selectedTicketType.isMembersOnly && !$rootScope.$storage.user.isAnonymousUser)) Utils.goTo('/tickets/prices/events');
    };

     // On member scan, continue
    $rootScope.$watch('$storage.user', function (user, oldUser) {
        if (user === oldUser) return;
        if (!$rootScope.$storage.selectedTicketType || !user || (user && user.isAnonymousUser)) return;
        if (($rootScope.$storage.selectedTicketType.isMembersOnly && user.isMember && user.prompt !== 'LapsedMembership') || !$rootScope.$storage.selectedTicketType.isMembersOnly) {
            // Get ticket one more time with member prices and continue flow
            $scope.getEvent(function () {
                $rootScope.continue();
            });
        }
    });

}]);

/*************************** Ticket Prices Controller ***************************/

App.controller('ticketPricesCtrl', ['$scope', '$rootScope', '$route', 'Tickets', 'Utils', 'Presentations', 'Cart', function ($scope, $rootScope, $route, Tickets, Utils, Presentations, Cart) {

    // One year from now
    $scope.oneYearFromNow = Utils.oneYearFromNow;

    // Handle filter on date change
    $scope.$on('datepickerChange', function (event, date) {
        if (!date || !$rootScope.$storage.selectedTicketType) return;
        Presentations.get(date, $rootScope.$storage.selectedTicketType.category);
    });

    // Redirect to cart or itinerary page
    $scope.continue = function () {
        // Attempt upsell first
        if ($rootScope.$storage.upsell.item && $route.current.$$route.upsell.types.indexOf($rootScope.$storage.upsell.item.typeName) !== -1) {
            // Show upsell slide push
            $rootScope.$storage.upsell.status = 'in progress';
            // Increase upsell count IF type is not donation
            if ($rootScope.$storage.upsell.item.typeName != 'Add-On Donation') $rootScope.$storage.upsell.count++;
        }
        // If category is GA then skip itinerary
        else if ($rootScope.$storage.selectedTicketType.category == 'GeneralAdmission' || $rootScope.$storage.selectedTicketType.category == 'SpecialEvents') Cart.add($rootScope.$storage.selectedTicketType, 'TicketCartItem');
        else Utils.goTo('/itinerary');
    };

    // On Upsell Accept (Sub)
    $scope.$on('upsellAccept', function (event, upsell) {
        // Cache old priceTypes
        var cachedPriceTypes = angular.copy($rootScope.$storage.selectedTicketType.priceTypes);
        // Get details for GA Plus One
        Tickets.getDetails(upsell.typeId, function (ticket) {
            // Transfer quantites
            Tickets.transferQuantites(cachedPriceTypes);
            // Continue with flow
            $scope.continue();
        });
    });

    // On Upsell Deny (Sub)
    $scope.$on('upsellDeny', function () {
        $scope.continue();
    });

}]);
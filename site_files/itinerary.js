/*************************** Itinerary Controllers ***************************/

App.controller('itineraryCtrl', ['$scope', '$rootScope', '$timeout', '$route', '$routeParams', '$alert', 'toaster', 'Utils', 'Exhibitions', 'Presentations', 'Tickets', 'Cart', function ($scope, $rootScope, $timeout, $route, $routeParams, $alert, toaster, Utils, Exhibitions, Presentations, Tickets, Cart) {

    // One year from now
    $scope.oneYearFromNow = Utils.oneYearFromNow;

    // Check if user has selected a date, otherwise set it to the current date
    if (!$rootScope.$storage.selectedTicketType.date) $rootScope.$storage.selectedTicketType.date = Utils.today;

    // Query Exhibitions
    $rootScope.$storage.spinner = true;
    Exhibitions.query(function (exhibitions) {
        $rootScope.$storage.spinner = false;
        // Add exhibitions to scope
        $scope.exhibitions = exhibitions;
        // Process presentations
        Presentations.attach($rootScope.$storage.selectedTicketType.presentations, exhibitions);
        // Force digest via timeout
        $timeout(function () {
            $rootScope.$broadcast('presentationsReady');
        });
    }, function () {
        $rootScope.$storage.spinner = false;
    });

    $scope.getTotalTicketQtys = Cart.getTotalTicketQtys;

    // Init cart options
    var cartOptions = {}; 

    // Ticket swap or upgrade
    if ($routeParams.edit) {
        // Return ticket for update and swap
        angular.extend(cartOptions, {
            returnTicket: {
                orderId: $routeParams.orderid || null,
                itemTypeId: $routeParams.ordertickettype || null,
                ticketNumber: $routeParams.ticketno || null,
                type: $routeParams.edittype || null
            }
        });
    }    

    // Handle filter on date change
    $scope.$on('datepickerChange', function (event, date) {
        if (!date || !$rootScope.$storage.selectedTicketType) return;
        console.log('datepickerChange');
        // Get presentations
        Presentations.get(date, $rootScope.$storage.selectedTicketType.category, function (presentations) {
            // Clear itirary
            $rootScope.$storage.itinerary.length = 0;
            // Re-attach presentations to exhibitions
            Presentations.attach(presentations, $scope.exhibitions);
            // Add selected property to each presentation
            Presentations.unselect(presentations);
            // Broadcast presentations ready event for presentationTimes directive
            $rootScope.$broadcast('presentationsReady');
        });
    });

    // Redirect to cart or itinerary page
    $scope.continue = function () {
        // Attempt upsell first
        if ($rootScope.$storage.upsell.item && $route.current.$$route.upsell && $route.current.$$route.upsell.types.indexOf($rootScope.$storage.upsell.item.typeName) !== -1) {
            // Show upsell slide push
            $rootScope.$storage.upsell.status = 'in progress';
            // Increase upsell count IF type is not donation
            if ($rootScope.$storage.upsell.item.typeName != 'Add-On Donation') $rootScope.$storage.upsell.count++;
        }
        // Check that user selected more exhibits
        else if ($rootScope.$storage.selectedTicketType.category == 'SuperSaver' && $rootScope.$storage.itinerary.length < 2) {
            // Alert user about redeem
            var itineraryAlert = $alert({
                template: '/Content/views/itinerary/itinerary-choose-more-alert.html',
                show: true
            });

            // Show alert
            $timeout(function () {
                itineraryAlert.show();
            }, 500);

            // Dismiss alert
            $scope.$on('$destroy', function () {
                itineraryAlert.hide();
            });

        }
        // Otherwise add to cart
        else {
            // Add selected presentation
            angular.extend(cartOptions, { selectedPresentations: $rootScope.getSelectedPresentations(true) });
            // Add item to cart
            Cart.add($rootScope.$storage.selectedTicketType, 'TicketCartItem', cartOptions);
        }
    };

    // On Upsell Accept (Sub)
    $scope.$on('upsellAccept', function (event, upsell) {
        if (upsell.cartItemType === 'Ticket') {
            // Cache old priceTypes
            var cachedPriceTypes = angular.copy($rootScope.$storage.selectedTicketType.priceTypes);
            // Get details for GA Plus One
            Tickets.getDetails(upsell.typeId, function (ticket) {
                // Transfer quantites
                Tickets.transferQuantites(cachedPriceTypes);
                // Broadcast presentations ready event for presentationTimes directive
                $rootScope.$broadcast('presentationsReady');
                // Continue with flow
                $scope.continue();
            });
        }
        if (upsell.cartItemType === 'Membership') {
            Cart.add($rootScope.$storage.selectedTicketType, 'TicketCartItem', $rootScope.getSelectedPresentations(), function () {
                Utils.goTo('/memberships/' + upsell.typeId + '/details');
            });
        }
    });

    // On Upsell Deny (Sub)
    $scope.$on('upsellDeny', function () {
        $scope.continue();
    });

}]);
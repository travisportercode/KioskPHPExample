/*************************** Itinerary Controllers ***************************/

App.controller('groupsCtrl', ['$scope', '$rootScope', '$timeout', '$route', '$routeParams', '$log', '$document', 'filterFilter', 'toaster', 'Utils', 'Exhibitions', 'Presentations', 'Tickets', 'Cart', 'Groups', 'Storage', 'Constants',
    function ($scope, $rootScope, $timeout, $route, $routeParams, $log, $document, filterFilter, toaster, Utils, Exhibitions, Presentations, Tickets, Cart, Groups, Storage, Constants) {

    /*Temporary for dev purposes*/
    if ($rootScope.$storage.user && $rootScope.$storage.user.isAnonymousUser) {
        $rootScope.login('d3schoolgroup@yahoo.com', 'Door3test');
    }

    /*Goto step in wizard*/
    $scope.gotoStep = function (stepName, id, options) {
        if (angular.isUndefined(stepName) && typeof step !== 'string') return $log.error('stepName argument invalid in gotoStep');
        var step = $rootScope.$storage.groups[stepName];
        if (!step) return $log.error('step invalid in gotoStep');
        var options = angular.extend({
            duration: 400
        }, options);
        //Step Validation
        //...
        //...
        //Enable the next step
        $rootScope.$storage.groups[stepName].disabled = false;
        //Capture element to scroll to and scroll to it
        //Settimeout to make sure element exists first
        $timeout(function () {
            var el = angular.element(document.getElementById(id));
            $document.scrollToElement(el, 0, options.duration);
        }, 100);
    };

    //Suppressing upsell on cart review for schoolgroups
    $route.routes['/cart/review'].upsell = false;
    $route.routes['/cart/payment'].upsell = false;

    $scope.continue = function () {
        $rootScope.$storage.cart.continueShopping = false;
        $rootScope.$storage.cart.showPresentationTimes = false;
        $rootScope.$storage.selectedTicketType.date = $rootScope.$storage.groups.tripDate.date;
        
        var cartOptions = {
            groupType: 'School',
            gradeList: $rootScope.$storage.groups.planYourDay.formData.selectedGrades || [],
            numberOfSelectedExhibitions: filterFilter($rootScope.$storage.itinerary, { forExhibitions: true }).length,
            selectedPresentations: Presentations.getSelected(true),
            selectedTransportation: {
                presentationId: $rootScope.$storage.groups.transportation.performanceId || null,
                selectedPriceTypes: {}
            },
            departureTime: $rootScope.$storage.groups.planYourDay.formData.departureTime || new Date(),
            admissionDate: $rootScope.$storage.selectedTicketType.date || new Date()
        };

        console.log(cartOptions.gradeList);

        cartOptions.selectedTransportation.selectedPriceTypes[$rootScope.$storage.groups.transportation.formData.priceTypeId] = ($rootScope.$storage.groups.transportation.formData.priceTypeGroup != Constants.groups.busGroupType) ?  1 : $rootScope.$storage.groups.transportation.formData.priceTypeQty;
        Cart.add($rootScope.$storage.selectedTicketType, 'GroupTicketCartItem', cartOptions);
    };

    // Handle filter on date change
    $scope.$on('datepickerChange', function (event, date) {
        if (!date || !$rootScope.$storage.selectedTicketType) return;

        //Reset steps
        Groups.resetSteps(['transportation', 'planYourDay', 'hallOfFocus'], function () {
            $scope.gotoStep('tripDate', 'groups-trip-date', {duration: 100});
        });

        //Reset selected presentations
        if ($rootScope.$storage.selectedTicketType) $rootScope.$storage.selectedTicketType = angular.copy(Storage.defaults.session.selectedTicketType);
        
        //Reset Itinerary
        if ($rootScope.$storage.itinerary) $rootScope.$storage.itinerary = angular.copy(Storage.defaults.session.itinerary);
    });

}]);
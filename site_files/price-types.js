/*************************** Directives ***************************/

/** 
  * Price Types
  */
App.directive('priceTypes', ['$rootScope', '$window', '$timeout', function ($rootScope, $window, $timeout) {
    return {
        templateUrl: '/Content/views/directives/price-types.html',
        restrict: 'E',
        link: function (scope, element, attrs) {

            // Options
            scope.showLabel = !!(attrs.showLabel === 'true');
            scope.showDescription = !!(attrs.showDescription === 'true');
            scope.dropdownMode = !!(attrs.dropdownMode === 'true');
            scope.compactMode = !!(attrs.compactMode === 'true');
            scope.hidePrices = !!(attrs.hidePrices === 'true');
            scope.maxQuantity = parseInt(attrs.maxQuantity) || 20;

            // Toggle functionality
            scope.showContent = false;
            scope.toggleContent = function () {
                scope.showContent = !scope.showContent;
                // Fake throbber for better ux
                if (!scope.showContent) {
                    $rootScope.$storage.spinner = true;
                    $timeout(function () {
                        $rootScope.$storage.spinner = false;
                    }, 500);
                }
            };

            // Quanity population
            scope.quantities = function () {
                var qty = 0, quantities = [], maxQty = (attrs.maxQuantity ? parseFloat(attrs.maxQuantity) : 20);
                for (qty ; qty <= maxQty ; qty++) quantities.push(qty)
                return quantities;
            };
           
            // Add/set 0 as quantity to each price type
            scope.resetQuantity = function () {
                angular.forEach(scope.ticketPrices, function (priceType) {
                    if (!priceType.quantity) priceType.quantity = 0;
                });
            };

            // Expose ticket prices to scope based on ticket types
            scope.ticketPrices = [];
            scope.$watch('$storage.selectedTicketType.priceTypes', function (priceTypes) {
                if (!priceTypes) return;
                scope.ticketPrices = priceTypes;
                // Reset quantity
                scope.resetQuantity();
            });            

            // Get total ticket quantity
            scope.getTotalTicketQtys = function () {
                var ticketQtys = 0;
                angular.forEach(scope.ticketPrices, function (ticket) {
                    if (ticket.quantity != 0) ticketQtys += ticket.quantity;
                });
                return ticketQtys;
            };

            // Expose ticket total quantites
            $rootScope.getTotalTicketQtys = scope.getTotalTicketQtys;
           
            // Get total ticket price
            scope.getTotalTicketPrice = function () {
                var totalPrice = 0;
                angular.forEach(scope.ticketPrices, function (ticket) {
                    if (ticket.quantity != 0) totalPrice += (ticket.quantity * ticket.price);
                });
                return totalPrice;
            };

            // Hide content on blur
            angular.element($window.document.body).on('click', function (e) {
                if (scope.dropdownMode && !element.has(angular.element(e.target)).length) {
                    scope.showContent = false;
                    scope.$apply();
                }
            });

        }
    }

}]);
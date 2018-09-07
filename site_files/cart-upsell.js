/*************************** Directives ***************************/

/** 
  * Cart Upsell
  */
App.directive('cartUpsell', ['$rootScope', '$log', 'Upsell', 'Cart', function ($rootScope, $log, Upsell, Cart) {
    return {
        templateUrl: '/Content/views/directives/cart-upsell.html',
        restrict: 'E',
        link: function (scope, element, attrs) {

            // Options
            scope.type = attrs.type;
            scope.params = scope.$eval(attrs.params) || {};

            // Expose rootscope to view via scope
            scope.upsell = $rootScope.$storage.upsell;
            scope.toggleCheck = $rootScope.toggleCheck;

            // Listen for the upsellReady event
            $rootScope.$on('upsellReady', function (event, upsell) {
                // Upsell options
                var options = {
                    upsellType: (upsell && upsell.categories[0] == 'donation') ? upsell.categories[0] : null,
                    upsellZip: ($rootScope.$storage.user && $rootScope.$storage.user.postalCode) ? $rootScope.$storage.user.postalCode : null
                };
                // Check if cart is eligible for an upsell
                $rootScope.$storage.spinner = true;
                Upsell.save(Cart.getProcessedCart(true, options), function (response) {
                    $rootScope.$storage.spinner = false;
                    // Check if upsell is allowed based on the number of previous upsells
                    if (scope.upsell.count < scope.upsell.allowed || response.typeName == 'Add-On Donation') {
                        // Select option if only 1
                        response.selectedOptions = (response.options && !response.options.length) ? response.typeId : [];
                        // Add upsell data to scope
                        scope.upsell.item = response[0] != 'n' ? response : null;
                    }
                    // Log upsellReady
                    $log.log('$broadcast.upsellReady', upsell.categories[0], scope.upsell);
                });
            });
            
            // Accept upsell
            scope.accept = function () {
                // Cache selected options for broadcast event
                var upsell = scope.upsell.item;
                // Disable next upsell
                scope.upsell.item = null;
                // Complete upsell status
                scope.upsell.status = 'complete';
                // Broadcast upsell accept and provide upsell object
                $rootScope.$broadcast('upsellAccept', upsell);
            };
         
            // Deny Upsell
            scope.deny = function () {
                // Disable next upsell
                scope.upsell.item = null;
                // Update upsell status
                scope.upsell.status = 'denied';
                // Broadcast upsell denied
                $rootScope.$broadcast('upsellDeny');
            };

        }
    }

}]);
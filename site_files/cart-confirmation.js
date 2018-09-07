/*************************** Cart Confirmation ***************************/

App.controller('cartConfirnationCtrl', ['$scope', '$rootScope', '$timeout', 'Presentations', 'Tickets', 'Cart', 'Utils', function ($scope, $rootScope, $timeout, Presentations, Tickets, Cart, Utils) {

    // Print ticket handler
    $scope.print = function () {
        Tickets.print($rootScope.$storage.confirmation.orderId);
    };

    // Go to start after print is done
    $scope.$watch('$storage.confirmation.printing', function (status) {
        if (status == 'done') {
            $timeout(function () {
                Utils.goTo('/');
            }, 2000);
        }
    });

    // Clear itinerary
    Presentations.clearSelected();

    // Clear selected ticket type
    Tickets.clearSelected();

    // Clear cart
    Cart.empty();

}]);
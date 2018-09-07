/*************************** Membership Controller ***************************/

App.controller('membershipCtrl', ['$scope', '$rootScope', '$timeout', '$alert', '$routeParams', 'MembershipLevels', 'Memberships', 'Membership', 'filterFilter', 'Cart', function ($scope, $rootScope, $timeout, $alert, $routeParams, MembershipLevels, Memberships, Membership, filterFilter, Cart) {

    // Init membership and member obj
    $scope.membership = {};
    $scope.member = {};

    // Init cart options
    var cartOptions = {};

    // Reset cart items status for 'draft' items
    Cart.resetCartStatus();

    /**
    * Membership turn Back 
    */

    if ($routeParams.orderId && $routeParams.ticketId) {

        // Return ticket for update and swap
        angular.extend(cartOptions, {
            returnTicket: {
                orderId: $routeParams.orderId || null,
                ticketNumber: $routeParams.ticketId || null,
                type: $routeParams.edittype || null
            }
        });

        // Alert user about redeem
        var redeemAlert = $alert({
            template: '/Content/views/membership/membership-redeem-alert.html',
            animation: 'am-fade-and-slide-top',
            placement: 'top',
            type: 'success',
            duration: 0,
            show: false
        });

        // Show alert
        $timeout(function () {
            redeemAlert.show();
        }, 500);

        // Dismiss alert
        $scope.$on('$destroy', function () {
            redeemAlert.hide();
        });

    }

    /**
    * Memberships Join 
    */

    $rootScope.$storage.spinner = true;

    MembershipLevels.query(function (levels) {

        $scope.membershipLevels = levels;

        // Memberships Types Array
        Memberships.query(function (memberships) {
            $scope.memberships = memberships;
            $rootScope.$storage.spinner = false;
        });

        /**
         * Membership Detail
         */

        if ($routeParams.membershipId) {

            // Prefill fields for logged in users
            if ($rootScope.$storage.user.hasOwnProperty('emailAddress')) $scope.member.primaryEmailAddress = $rootScope.$storage.user.emailAddress;
            if ($rootScope.$storage.user.hasOwnProperty('firstName')) $scope.member.primaryFirstName = $rootScope.$storage.user.firstName;
            if ($rootScope.$storage.user.hasOwnProperty('lastName')) $scope.member.primaryLastName = $rootScope.$storage.user.lastName;

            $rootScope.$storage.spinner = true;

            Membership.get({ membershipId: $routeParams.membershipId }, function (membership) {

                // Inherit image from parent
                membership.detailImage = filterFilter($scope.membershipLevels, { id: membership.levelId })[0].detailImage || '';

                // Add membership to scope
                $scope.membership = membership;

                // Init member obj and attach memberTypeId
                $scope.member.membershipTypeId = $scope.membership.membershipTypeId;

                $rootScope.$storage.spinner = false;

            });

        }

    });

    /**
    * Memberships Continue 
    */

    $rootScope.continue = function () {
        // Persist primary email address if provided
        if ($scope.member.primaryEmailAddress) $rootScope.$storage.user.emailAddress = $scope.member.primaryEmailAddress;
        // Add member details to cart item
        angular.extend(cartOptions, $scope.member);
        // Add to cart
        Cart.add($scope.membership, 'MembershipCartItem', cartOptions);
    };

}]);

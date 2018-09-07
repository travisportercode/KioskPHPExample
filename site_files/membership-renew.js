/*************************** Memebership Renew Controller ***************************/

App.controller('membershipRenew', ['$scope', '$rootScope', 'toaster', 'RenewMembership', function ($scope, $rootScope, toaster, RenewMembership) {

    $rootScope.$storage.spinner = true;
    
    // Check for user session
    if (!$rootScope.$storage.user) {
        toaster.pop({
            type: 'error',
            body: 'User is null in local storage.'
        });
        $rootScope.goTo('/welcome');
    }

    // Prevent renewal for anonymous users
    if ($rootScope.$storage.user.isAnonymousUser) {
        toaster.pop({
            type: 'error',
            body: 'Anonymous user attempted membership renewal. Rerouting to home.'
        });
        $rootScope.goTo('/welcome');
    }

    // Membership Review API call
    RenewMembership.update($rootScope.$storage.user, function (cart) {
        Cart.store(cart);
        $rootScope.$storage.spinner = false;
        $rootScope.goTo('/cart/review');
    }, function (response) {
        toaster.pop({
            type: 'error',
            body: 'Renew request failed'
        });
        $rootScope.$storage.spinner = false;
        $rootScope.goTo('/welcome');
    });

}]);
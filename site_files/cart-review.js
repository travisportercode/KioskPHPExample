/*************************** Cart Review Controller ***************************/

App.controller('cartReviewCtrl', ['$scope', '$rootScope', 'Cart', 'User', 'Utils', 'Constants', function ($scope, $rootScope, Cart, User, Utils, Constants) {

    $scope.hasGroupItem = false;

    // Get total quantities
    $scope.getItemPriceTypesQtys = function (priceType) {
        if (!priceType) return;
        var itemQtys = 0;
        angular.forEach(priceType, function (item) {
            if (item.quantity != 0) itemQtys += item.quantity;
        });
        return itemQtys;
    };

    // Flag cart items status as draft
    $scope.flagCartAsDraft = function () {
        if ($rootScope.$storage.cart.items.length)
            $rootScope.$storage.cart.items[$rootScope.$storage.cart.items.length - 1].status = 'draft';
    };

    // Continue to checkout
    $rootScope.continue = function () {
        if (!$rootScope.$storage.cart.items.length) return;
        console.log('Cart Review Continue()');
        // Attempt upsell first
        if ($rootScope.$storage.upsell.item && !Cart.editMode()) {
            // Show upsell slide push
            $rootScope.$storage.upsell.status = 'in progress';
            // Increase upsell count IF type is not donation
            if ($rootScope.$storage.upsell.item.typeName != 'Add-On Donation') $rootScope.$storage.upsell.count++;
        }
        // Check user's eligibility for sign up
        else if (User.isElegibleForSignUp()) User.promptForSignUp();
        // Otherwise proceed to checkout
        else if (Cart.isExpress()) Cart.expressCheckout();
        // Otherwise proceed to checkout
        else $rootScope.goTo('/cart/payment');
    };

    // On Upsell Accept (Sub)
    $scope.$on('upsellAccept', function (event, upsell) {
        Cart.add({}, 'DonationCartItem', { donateType: 'UpSell', basePrice: upsell.selectedOptions[0] }, function () {
            // Cart continue
            $scope.continue();
        });
    });

    // On Upsell Deny (Sub)
    $scope.$on('upsellDeny', function () {
        $scope.continue();
    });

    // Promt for removal functionality
    $scope.promptToggle = {};

    // Add/Reset item promptState values and set hasGroupType
    if ($rootScope.$storage.cart) {
        angular.forEach($rootScope.$storage.cart.items, function (item) {
            item.promptState = false;
            if (item.groupType == Constants.groups.schoolGroupType) $scope.hasGroupItem = true;
        });

    }

    $scope.togglePromptState = function (item) {
        if (!item) return console.error('item passed to togglePromptState is invalid.');
        item.promptState = !item.promptState;
    };

    $scope.promptToggleHandler = function (item) {
        if (!item) return console.error('item passed to promptToggleHanlder is invalid.');
        if (item.affectsPricing) {
            $scope.togglePromptState(item);
        } else {
            Cart.remove(item.itemTypeId);
        }
    };

}]);
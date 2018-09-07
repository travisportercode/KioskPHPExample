/*************************** Cart Payment Controller ***************************/

App.controller('cartPaymentCtrl', ['$scope', '$rootScope', '$timeout', '$route', 'filterFilter', 'toaster', 'Cart', 'User', 'Utils', function ($scope, $rootScope, $timeout, $route, filterFilter, toaster, Cart, User, Utils) {

    // Initialize user obj
    $scope.user = ($rootScope.$persistentStorage.devMode ? angular.copy($rootScope.$persistentStorage.testUser) : {});

    // Initialize payment obj
    $scope.payment = ($rootScope.$persistentStorage.devMode ? angular.copy($rootScope.$persistentStorage.testPayment) : {});

    // Submit Order
    $scope.orderSubmit = function () {

        // Post order to API
        Cart.checkout({
            emailAddress: $rootScope.$storage.user.emailAddress,
            cardholderName: $rootScope.$storage.user.firstName + ' ' + $rootScope.$storage.user.lastName,
            firstName: $rootScope.$storage.user.firstName,
            lastName: $rootScope.$storage.user.lastName,
            cardNo: $scope.payment.cardNo,
            cardExpiryYear: $scope.payment.cardExpiryYear,
            cardExpiryMonth: $scope.payment.cardExpiryMonth,
            cardSecurityCode: $scope.payment.cardSecurityCode,
            password: $scope.user.password,
            address1: $rootScope.$storage.user.address1,
            address2: $rootScope.$storage.user.address2 || '',
            city: $rootScope.$storage.user.city,
            stateProvince: ($scope.user.state == '' ? { stateCode: null, name: '' } : $scope.user.state),
            countryId: $scope.user.country,
            postalCode: $rootScope.$storage.user.postalCode,
            ccEmailList: $rootScope.$storage.user.ccEmailList || []
        });

    };

    // Check if email is valid for name collission
    $scope.isEmailUnique = function (email) {
        if (!$scope.user.password || !email) return;
        User.isEmailUnique(email, function () {
            $scope.paymentForm.email.$setValidity('isEmailUnique', true);
        }, function () {
            toaster.pop({
                type: 'error',
                body: 'Email already exists in the system.  Please log in with this email address or choose a new one.',
                options: {
                    'position-class': 'toast-top-full-width'
                }
            });
            $scope.paymentForm.email.$setValidity('isEmailUnique', false);
        });
    };

    // Trigger upsell after zipCode is entered
    $scope.checkForUpsell = function () {
        $timeout(function () {
            if ($rootScope.$storage.user.postalCode.length >= 5 && $route.current.$$route.upsell && $route.current.$$route.upsell.breakpoints.indexOf($rootScope.breakpoint) !== -1 && !$rootScope.$persistentStorage.supress.upsell) $rootScope.$broadcast('upsellReady', $route.current.$$route.upsell);
        });
    };

    // Continue to checkout
    $scope.continue = function () {
        // Attempt upsell first
        if ($rootScope.$storage.upsell.item) {
            // Show upsell slide push
            $rootScope.$storage.upsell.status = 'in progress';
            // Increase upsell count IF type is not donation
            if ($rootScope.$storage.upsell.item.typeName != 'Add-On Donation') $rootScope.$storage.upsell.count++;
        }
        // Otherwise submit order
        else $scope.orderSubmit();
    };

    // On Upsell Accept (Sub)
    $scope.$on('upsellAccept', function (event, upsell) {
        if (upsell.cartItemType === 'Membership') Utils.goTo('/memberships/' + upsell.typeId + '/details');
    });

    // On Upsell Deny (Sub)
    $scope.$on('upsellDeny', function () {
        $scope.continue();
    });

    // Get all countries
    Cart.getCountries(function (countries) {
        $scope.countries = countries;
        // Pre-select country from storage
        if ($rootScope.$storage.user && $rootScope.$storage.user.countryId) {
            $scope.user.country = $scope.countries[$scope.countries.indexOf(filterFilter($scope.countries, { id: $rootScope.$storage.user.countryId })[0])];
            if ($rootScope.$storage.user.stateProvince) $scope.getStatesFromCountry($scope.user.country.id)
        } 
        else if ($rootScope.$storage.user.isAnonymousUser || $rootScope.$persistentStorage.devMode) $scope.user.country = $scope.countries[0];
        if ($scope.user.country) $scope.getStatesFromCountry($scope.user.country.id);
    });

    // Get states from selected country
    $scope.getStatesFromCountry = function (countryId) {
        $scope.user.state = '';
        $scope.states = [];
        Cart.getStates(countryId, function (states) {
            $scope.states = states;
            if (!$rootScope.$storage.user.isAnonymousUser && $rootScope.$storage.user.stateProvince) $scope.user.state = $scope.states[$scope.states.indexOf(filterFilter($scope.states, { stateCode: $rootScope.$storage.user.stateProvince.stateCode })[0])];
            if ($rootScope.$persistentStorage.devMode) $scope.user.state = $scope.states[39];
        });
    };

}]);
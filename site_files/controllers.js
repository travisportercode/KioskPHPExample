/*************************** Controllers ***************************/

/** 
  * Main Controller
  */
App.controller('mainCtrl', ['$scope', '$rootScope', '$timeout', '$location', '$routeParams', 'toaster', 'Storage', 'Cart', 'Presentations', 'Utils', 'Tickets', 'User',
    function ($scope, $rootScope, $timeout, $location, $routeParams, toaster, Storage, Cart, Presentations, Utils, Tickets, User) {

    /**
    * Initialize Storage service
    */
    Storage.session.start();
    Storage.local.start();

    // Ensure sure spinner goes away on every fail
    $rootScope.$storage.spinner = false;

    /**
     * Services exposed to rootScope
     */

    // Route and Location
    $rootScope.location = $location;
    $rootScope.routeParams = $routeParams;

    // Utils
    $rootScope.goTo = Utils.goTo;
    $rootScope.goBack = Utils.goBack;
    $rootScope.reload = Utils.reload;
    $rootScope.resetRadios = Utils.resetRadios;
    $rootScope.toggleCheck = Utils.toggleCheck;
    $rootScope.stateRegex = Utils.stateRegex;
    $rootScope.zipRegex = Utils.zipRegex;
    $rootScope.postalCodeRegex = Utils.postalCodeRegex;
    $rootScope.validateEmail = Utils.validateEmail;
    $rootScope.parseCardData = Utils.parseCardData;
    $rootScope.alert = Utils.alert;
    $rootScope.parseErrors = Utils.parseErrors;
    $rootScope.years = Utils.years;
    $rootScope.months = Utils.months;
    $rootScope.countries = Utils.countries;
    $rootScope.getDocumentURL = Utils.getDocumentURL;
    $rootScope.getHostname = Utils.getHostname;

    // Tickets
    $rootScope.getSelectedPriceTypes = Tickets.getSelected;

    // Cart
    $rootScope.addToCart = Cart.add;
    $rootScope.removeFromCart = Cart.remove;
    $rootScope.editMode = Cart.editMode;
    $rootScope.eventMode = Cart.eventMode;

    // Presentations
    $rootScope.getSelectedPresentations = Presentations.getSelected;

    // Storage
    $rootScope.resetSessionStorage = Storage.session.reset;
    $rootScope.resetLocalStorage = Storage.local.reset;
    $rootScope.resetStorage = Storage.reset;

    /* Login */
    $rootScope.login = function (username, password) {
        if (angular.isDefined(username)) User.login({ username: username, password: (password || '') });
    };

    /* Logout */
    $rootScope.logout = function () {
        User.logout(function () {
            Storage.session.reset();
            User.loginAsAnonymous(function () {
                Utils.clearParams();
                Utils.refresh();
            });
        });
    };

    /* Show login modal (personalization) */
    $rootScope.showIframeModal = function (template) {
        User.showIframeModal(template);
    };

    /* Hide login modal (personalization) */
    $rootScope.hideIframeModal = function () {
        User.hideIframeModal();
    };

    /* User state handling */
    $rootScope.$watch('$storage.user', function (user, oldUser) {
        // Log user obj
        console.log(user);
        // Update cart on member login
        if (!user || user === oldUser) return;
        // Get updated cart
        Cart.get();
        // Welcome user.
        User.welcome();
        // User alert handling
        User.alertMembershipRenewal();
        User.alertMembershipLapsed();
    });

    $rootScope.scrolltoHref = function (id) {
        // set the location.hash to the id of
        // the element you wish to scroll to.
        $location.hash(id);
        // call $anchorScroll()
        $anchorScroll();
    };

    
    
}]);
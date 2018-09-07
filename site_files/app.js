'use strict';

var App = angular.module('App', [
    'ngRoute',
    'ngTouch',
    'ngSanitize',
    'ngResource',
    'ngStorage',
    'ngAnimate',
    'toaster',
    'mgcrea.ngStrap',
    'angular-flexslider',
    'pascalprecht.translate',
    'duScroll'
]);

App.run(['$rootScope', '$route', '$http', '$log', 'User', 'Storage', 'Utils', 'Constants', function ($rootScope, $route, $http, $log, User, Storage, Utils, Constants) {

    /* Start new session */
    $rootScope.$on('$locationChangeStart', function (event, next, current) {

        $http.defaults.headers.common.Authorization = 'bearer '+User.getBearerToken();
        var cookieAuthorization = $.cookie(Constants.cookieKeys.authorization);

        // Handle authentication states with comments
        if (!User.isUserCookieSet() && !$rootScope.$storage.user) {
            $log.log('Session: Anonymous');
            event.preventDefault();
            User.loginAsAnonymous(function () {
                Utils.reload();
            });
        } else if (User.isUserCookieSet() && !$rootScope.$storage.user || (User.isUserCookieSet() && $rootScope.$storage.user && $rootScope.$storage.authorization != cookieAuthorization)) {
            $log.log('Session: SSO');
            event.preventDefault();
            Storage.session.reset();
            User.setUserFromCookie(function () { Utils.reload(); });
        } else if (!User.isUserCookieSet() && $rootScope.$storage.user) {
            $log.log('Session: Logout');
            event.preventDefault();
            User.logout(function () {
                Utils.refresh();
            });
        } else {
            $log.log('Session: Logged In');
        }

    });

    /* Extending routes */
    $rootScope.$on('$routeChangeSuccess', function () {

        if (!$route.current.$$route) return;

        // Add route custom values to rootScope
        $rootScope.bleed = !!($route.current.$$route.bleed);
        $rootScope.fullscreen = !!($route.current.$$route.fullscreen);

        // Backgrounds */
        //if ($route.current.$$route.background) {
        $rootScope.background = $route.current.$$route.background ? !!($route.current.$$route.background.image) : false;
        if (!$rootScope.$storage.background && $route.current.$$route.background) $rootScope.$storage.background = Math.floor(Math.random() * $route.current.$$route.background.random) + 1;
        //}
        
        // Broadcast based upsell eligibility
        if ($route.current.$$route.upsell && $route.current.$$route.upsell.breakpoints.indexOf($rootScope.breakpoint) !== -1 && !$rootScope.$persistentStorage.supress.upsell) $rootScope.$broadcast('upsellReady', $route.current.$$route.upsell);

        // Supress Mode security
        if ($route.current.$$route.originalPath === '/supress/:mode' && $rootScope.breakpoint !== 'xl') $rootScope.goTo('/welcome');

    });

}]);

App.config(['$routeProvider', '$httpProvider', '$translateProvider', '$translateStaticFilesLoaderProvider', '$datepickerProvider', '$modalProvider', '$alertProvider', '$tabProvider', function ($routeProvider, $httpProvider, $translateProvider, $translateStaticFilesLoaderProvider, $datepickerProvider, $modalProvider, $alertProvider, $tabProvider) {

    /**
     * Languages
     */
    $translateProvider.useStaticFilesLoader({
        prefix: '/Content/languages/',
        suffix: '.json'
    });

    // Set default language
    $translateProvider.preferredLanguage('enUS');

    /* Datepicker */
    angular.extend($datepickerProvider.defaults, {
        dateFormat: 'MM/dd/yyyy',
        startWeek: 7,
        autoclose: 1,
        template: '/Content/views/directives/datepicker-dropdown.html',
        trigger: 'click'
    });

    /* Modals */
    angular.extend($modalProvider.defaults, {
        animation: 'am-fade-and-slide-top'
    });

    /* Alerts */
    angular.extend($alertProvider.defaults, {
        animation: 'am-fade-and-slide-bottom',
        placement: 'bottom',
        type: 'warning',
        container: '#header'
    });

    /* Tabs */
    angular.extend($tabProvider.defaults, {
        animation: 'am-fade-and-slide-top'
    });

    /**
     * Routes
     */
    $routeProvider.
        /** 
          * Welcome
          */
        when('/welcome', {
            templateUrl: '/Content/views/home/welcome.html',
            fullscreen: false,
            beeld: true
        }).
        /**
          * Groups
          */
        when('/groups', {
            templateUrl: '/Content/views/groups/groups.html',
            controller: 'groupsCtrl'
        }).
        /** 
          * Tickets
          */
        when('/tickets', {
            templateUrl: '/Content/views/tickets/ticket-types.html',
            controller: 'ticketTypesCtrl',
            fullscreen: true,
            scan: true,
            background: {
                image: true,
                random: 4
            }
        }).
        when('/tickets/upgrade', {
            templateUrl: '/Content/views/tickets/ticket-types.html',
            controller: 'ticketUpgradeCtrl',
            fullscreen: true,
            background: {
                image: true,
                random: 4
            }
        }).
        when('/tickets/swap', {
            templateUrl: '/Content/views/tickets/ticket-types.html',
            controller: 'ticketSwapCtrl',
            fullscreen: true,
            background: {
                image: true,
                random: 4
            }
        }).
        when('/tickets/prices', {
            templateUrl: '/Content/views/tickets/ticket-prices.html',
            controller: 'ticketPricesCtrl',
            upsell: {
                categories: ['ticket'],
                breakpoints: ['xs', 'sm', 'md', 'lg', 'xl'],
                types: ['GeneralAdmissionPlusOne']
            }
        }).
         when('/tickets/prices/:events', {
             templateUrl: '/Content/views/tickets/ticket-prices.html',
             controller: 'ticketPricesCtrl',
             fullscreen: true,
             upsell: {
                 categories: ['ticket'],
                 breakpoints: ['xs', 'sm', 'md', 'lg', 'xl'],
                 types: ['GeneralAdmissionPlusOne']
             }
         }).
        when('/tickets/search', {
            templateUrl: '/Content/views/tickets/ticket-search.html',
            controller: ''
        }).
        when('/tickets/:ticketId/details', {
            templateUrl: '/Content/views/tickets/ticket-detail.html',
            controller: ''
        }).
        /** 
          * Itinerary
          */
        when('/itinerary', {
            templateUrl: '/Content/views/itinerary/itinerary.html',
            controller: 'itineraryCtrl',
            bleed: true,
            upsell: {
                categories: ['ticket','membership'],
                breakpoints: ['xs', 'sm', 'md', 'lg', 'xl'],
                types: ['SuperSaver', 'Family', 'Individual Member']
            }
        }).
        when('/itinerary/:edit', {
            templateUrl: '/Content/views/itinerary/itinerary.html',
            controller: 'itineraryCtrl',
            bleed: true
        }).
        /** 
          * Cart
          */
        when('/cart/review', {
            templateUrl: '/Content/views/cart/cart-review.html',
            controller: 'cartReviewCtrl',
            bleed: true,
            scan: true,
            swipe: true,
            upsell: {
                categories: ['donation'],
                breakpoints: ['xs', 'sm', 'md', 'lg', 'xl'],
                types: ['Add-On Donation']
            }
        }).
        when('/cart/payment', {
            templateUrl: '/Content/views/cart/cart-payment.html',
            controller: 'cartPaymentCtrl',
            upsell: {
                categories: ['membership'],
                breakpoints: ['xs', 'sm', 'md', 'lg'],
                types: ['Family', 'Individual Member']
            }
        }).
        when('/cart/confirmation/:orderId', {
            templateUrl: '/Content/views/cart/cart-confirmation.html',
            controller: 'cartConfirnationCtrl',
            fullscreen: true
        }).
        /** 
          * Admin 
          */
        when('/admin', {
            templateUrl: '/Content/views/admin/admin-settings.html',
            controller: 'adminCtrl'
        }).
        /** 
          * Memberships
          */
        when('/memberships/join', {
            templateUrl: '/Content/views/membership/membership-join.html',
            controller: 'membershipCtrl'
        }).
        when('/membership/renew', {
            templateUrl: '/Content/views/membership/membership-renew.html',
            controller: 'membershipRenew'
        }).
        when('/memberships/:membershipId/details', {
            templateUrl: '/Content/views/membership/membership-detail.html',
            controller: 'membershipCtrl'
        }).
        when('/memberships/:membershipId/redeem', {
            templateUrl: '/Content/views/membership/membership-detail.html',
            controller: 'membershipCtrl'
        }).
        /** 
          * Donation
          */
        when('/donation/:donationId/details', {
            templateUrl: '/Content/views/donation/donation-detail.html',
            controller: 'donationCtrl'
        }).
        /** 
          * Events
          */
        when('/event/:eventId', {
            templateUrl: '/Content/views/event/event-detail.html',
            controller: 'eventCtrl',
            fullscreen: true,
            scan: true
        }).
        /** 
          * Order
          */
        when('/order/:orderId/details', {
            templateUrl: '/Content/views/cart/cart-order-detail.html',
            controller: 'orderCtrl',
            fullscreen: true
        }).
        when('/order/:orderId/email/:emailAddress/type/:emailType', {
            templateUrl: '/Content/views/order/order-email-render.html',
            controller: 'orderCtrl',
            fullscreen: true
        }).
        /** 
          * User
          */
        when('/user/register', {
            templateUrl: '/Content/views/user/register.html',
            controller: 'register'
        }).
        when('/user/zip', {
            templateUrl: '/Content/views/user/user-zip.html',
            fullscreen: true,
            background: {
                image: true,
                random: 4
            }
        }).
        /** 
          * Error
          */
        when('/error', {
            templateUrl: '/Content/views/error/error.html'
        }).
        when('/error/:errorCode', {
            templateUrl: '/Content/views/error/error.html'
        }).
        /** 
          * Other
          */
        when('/2020', {
            templateUrl: '/Content/views/2020/2020.html',
            controller: 'twentyTwentyCtrl'
        }).
        when('/style-guide', {
            templateUrl: '/Content/views/style_guide/style-guide.html',
            controller: 'styleGuideCtrl'
        }).
        when('/supress/:mode', {
            templateUrl: '/Content/views/home/supress.html'
        }).
        otherwise({
            redirectTo: '/welcome'
        });

}]);
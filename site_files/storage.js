/** 
 * Storage Service - Local Storage Functionality based on ngStorage (url:https://github.com/gsklee/ngStorage)
 */

App.factory('Storage', ['$rootScope', '$sessionStorage', '$localStorage', 'toaster', function ($rootScope, $sessionStorage, $localStorage, toaster) {

    if (!$sessionStorage || !$localStorage) return;

    var Storage = {
        session: {},
        local: {},
        defaults: {
            session: {
                spinner: false,
                background: null,
                devMode: false,
                session: null,
                ticketQty: 1,
                selectedTicketType: null,
                myDayCollapsed: true,
                itinerary: [],
                groups: {
                    tripDate: {
                        tickets: [],
                        date: '',
                        formData: {},
                        disabled: true
                    },
                    transportation: {
                        formData: {
                            priceTypeId: null,
                            priceTypeGroup: null,
                            priceTypeQty: 0
                        },
                        disabled: true
                    },
                    hallOfFocus: {
                        halls: [],
                        hasPresentations: false,
                        formData: {
                            selectedHalls: []
                        },
                        disabled: true
                    },
                    planYourDay: {
                        grades: [],
                        arrivalTimePresentations: {},
                        lunchrooms: {
                            presentations: []
                        },
                        formData: {
                            departureTime: '',
                            selectedGrades: []
                        },
                        disabled: true
                    },
                    review: {
                        disabled: true
                    }
                },
                cart: {
                    items: [],
                    total: 0,
                    notes: [],
                    warning: null,
                    continueShopping: true,
                    showPresentationTimes: true
                },
                upsell: {
                    status: 'awaiting',
                    count: 0,
                    allowed: 1,
                    item: null
                },
                cc: null,
                confirmation: null,
                hasRegistered: false,
                user: null,
                membershipId: null,
                ticketId: null,
                orderId: null,
                ticketSwap: {
                    id: null,
                    quantity: 0,
                    success: null
                }
            },
            local: {
                device: null,
                eventMode: false,
                eventId: null,
                event: {},
                supress: {
                    upsell: false
                }
            }
        }
    };

    /**
    * Start Session Storage
    */
    Storage.session.start = function (callback) {
        $rootScope.$storage = $sessionStorage.$default(angular.copy(Storage.defaults.session));
        if (angular.isFunction(callback)) callback();
    };

    /**
    * Start Local Storage
    */
    Storage.local.start = function (callback) {
        $rootScope.$persistentStorage = $localStorage.$default(angular.copy(Storage.defaults.local));
        if (angular.isFunction(callback)) callback();
    };

    /**
    * Reset Session Storage
    */
    Storage.session.reset = function (callback) {
        $sessionStorage.$reset(angular.copy(Storage.defaults.session));
        toaster.pop({
            type: 'warning',
            body: 'Session storage cleared'
        });
        if (angular.isFunction(callback)) callback();
    };

    /**
    * Reset Local Storage
    */
    Storage.local.reset = function (callback) {
        $localStorage.$reset(angular.copy(Storage.defaults.local));
        toaster.pop({
            type: 'warning',
            body: 'Local storage cleared'
        });
        if (angular.isFunction(callback)) callback();
    };

    /**
    * Reset Storage
    */
    Storage.reset = function (callback) {
        Storage.session.reset();
        Storage.local.reset();
        if (angular.isFunction(callback)) callback();
    };
  
    return Storage;

}]);
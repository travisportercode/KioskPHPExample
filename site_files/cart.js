/** 
 * Cart Service
 */

App.factory('Cart', ['$rootScope', '$routeParams', 'filterFilter', 'Utils', 'Storage', 'CartItem', 'CartGet', 'CartItemDelete', 'Countries', 'StatesFromCountry', 'Payment', function ($rootScope, $routeParams, filterFilter, Utils, Storage, CartItem, CartGet, CartItemDelete, Countries, StatesFromCountry, Payment) {

    var Cart = {};

    /**
    * Flags cart item if item removal can affect ticket items (entitlements)
    */
    var updateAffectsPricing = function () {
        angular.forEach($rootScope.$storage.cart.items, function (item) {
            item.affectsPricing = !!(item.cartItemType == 'Membership' && filterFilter($rootScope.$storage.cart.items, { cartItemType: 'Ticket' }).length);
        });
    };

    /**
    * Save cart in storage
    */
    Cart.store = function (cart) {
        if (!cart) return;
        $rootScope.$storage.cart = angular.extend(Storage.defaults.session.cart, cart);
    };

    /**
    * Remove item from cart
    */
    Cart.remove = function (itemId, success, error) {
        if (!itemId) return;
        $rootScope.$storage.spinner = true;
        CartItemDelete.remove({ itemId: itemId }, function (cart) {
            Cart.store(cart);
            updateAffectsPricing();
            if (angular.isFunction(success)) success(cart);
            $rootScope.$storage.spinner = false;
        }, function (response) {
            Utils.showParsedErrors(response.data.messages, $rootScope.$persistentStorage.device);
            if (angular.isFunction(error)) error(response);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Update item from cart
    */
    Cart.saveCartItem = function (item, success, error) {
        if (!item) return;
        $rootScope.$storage.spinner = true;
        // Post cart item to API and expect a prossesed cart in return
        CartItem.save(JSON.stringify(item), function (cart) {
            if (cart[0] == 'n') return;
            Cart.store(cart);
            updateAffectsPricing();
            if (angular.isFunction(success)) success(cart);
            $rootScope.$storage.spinner = false;
        }, function (response) {
            Utils.showParsedErrors(response.data.messages, $rootScope.$persistentStorage.device);
            if (angular.isFunction(error)) error(response);
            $rootScope.$storage.spinner = false;
        });
    };

    /**
    * Get total ticket quantity
    */
    Cart.getTotalTicketQtys = function () {
        var ticketPrices = $rootScope.$storage.selectedTicketType ? $rootScope.$storage.selectedTicketType.priceTypes : [];
        var ticketQtys = 0;
        angular.forEach(ticketPrices, function (ticket) {
            if (ticket.quantity != 0) ticketQtys += ticket.quantity;
        });
        return ticketQtys || 0;
    };

    /**
    * Item as cart ttem - Parse ticket, donation or membership as a cart item for back-end
    */
    Cart.itemAsCartItem = function (item, type, options) {

        if (!item || !type) return;

        // Cart item model
        var cartItem = {
            $type: (type ? 'AmnhDigital.Ticketing.Entities.' + type + ', AmnhDigital.Ticketing.Entities' : ''),
            type: type.replace('CartItem', '').toLowerCase(),
            itemTypeid: item.id || null,
            category: (type == 'TicketCartItem') ? item.category : null,
            cartItemType: item.cartItemType,
            name: item.name || item.title,
            longDescription: item.name || item.title,
            description: item.description || '',
            price: item.price,
            adjustedItemTotal: item.price,
            affectsPricing: false,
            quantity: (type == 'TicketCartItem') ? Cart.getTotalTicketQtys() : 1,
            selectedPriceTypes: (type == 'TicketCartItem' || type == 'GroupTicketCartItem') ? $rootScope.getSelectedPriceTypes(true) : null
        };

        // Apply additional properties to cart item
        if (options) {
            angular.forEach(options, function (value, key) {
                cartItem[key] = value;
            });
        }

        return cartItem;
    };

    /**
    * Add to cart functionality
    */
    Cart.add = function (item, type, options, success, error) {

        // Success Route
        var successRoute = '/cart/review';

        // Enable spinner
        $rootScope.$storage.spinner = true;

        // Parsed cart item
        var cartItem = Cart.itemAsCartItem(item, type, options);

        // Delete current items in draft mode
        var draftItem = filterFilter($rootScope.$storage.cart.items, { status: 'draft' });

        if (draftItem.length) {
            // Update cart item to API and expect a prossesed cart in return
            Cart.remove(draftItem[0].itemTypeId, function () {
                Cart.saveCartItem(cartItem, function () {
                    Utils.goTo(successRoute);
                    // Apply callback
                    if (angular.isFunction(success)) success();
                });
            });
        } else {
            // Post cart item to API and expect a prossesed cart in return
            Cart.saveCartItem(cartItem, function () {
                Utils.goTo(successRoute);
                // Apply callback
                if (angular.isFunction(success)) success();
            });
        }

    };

    // Process cart (back-end)
    Cart.get = function (cart, success, error) {
        if (!cart) cart = {};
        $rootScope.$storage.spinner = true;
        CartGet.get(cart, function(cart) {
            Cart.store(cart);
            if (angular.isFunction(success)) success(cart);
            $rootScope.$storage.spinner = false;
        }, function (response) {
            if (angular.isFunction(error)) success(response);
            $rootScope.$storage.spinner = false;
        })
    };

    // Reset cart items status
    Cart.resetCartStatus = function () {
        if ($rootScope.$storage.cart.items.length) {
            angular.forEach($rootScope.$storage.cart.items, function (item) {
                delete item.status;
            });
        }
    };

    // Parse cart for Back-End
    Cart.getProcessedCart = function (stringify, options) {
        // Check for cart in storage
        if (!$rootScope.$storage.cart) return;
        // Temporary variables
        var cart = {}, cleanCart = [], items = angular.copy($rootScope.$storage.cart.items);
        // Remove front-end only properties
        angular.forEach(items, function (item, key) {
            delete item.name;
            delete item.description;
            delete item.type;
            delete item.$$hashKey;
            cleanCart.push(item);
        });
        // Cart model
        cart = {
            items: cleanCart,
            total: $rootScope.$storage.cart.total || null,
            upsellCount: $rootScope.$storage.upsell.count || 0,
            draftTicketCartItem: Cart.itemAsCartItem($rootScope.$storage.selectedTicketType, 'TicketCartItem') || null,
            notes: [],
            warning: null
        };
        // Apply additional properties to full cart
        if (options) {
            angular.forEach(options, function (value, key) {
                cart[key] = value;
            });
        }
        // Stringify cart based on stringify boolean
        if (stringify) return JSON.stringify(cart)
        else return cart;
    };

    // Get all countries
    Cart.getCountries = function (callback) {
        var countries = [];
        Countries.query(function (response) {
            angular.forEach(response, function (value, key) {
                if (value.description != null) {
                    countries.push({
                        id: value.id,
                        name: value.description
                    });
                }
            });
            if (angular.isFunction(callback)) callback(countries);
            return countries;
        });
    };
    
    // Get states from selected country
    Cart.getStates = function (countryId, callback) {
        StatesFromCountry.query({ countryId: countryId }, function (response) {
            var states = [];
            if (response.length) {
                angular.forEach(response, function (value, key) {
                    if (value.description != null) {
                        states.push({
                            stateCode: value.id,
                            name: value.description
                        });
                    }
                });
                if (angular.isFunction(callback)) callback(states);
                return states;
            }
        });
    };

    // Process order
    Cart.checkout = function (order, success, error) {
        if (!order) return;
        else angular.extend(order, { cart: Cart.getProcessedCart() });
        $rootScope.$storage.spinner = true;
        Payment.save(JSON.stringify(order), function (response) {
            $rootScope.$storage.spinner = false;
            // Save confirmation information in storage
            $rootScope.$storage.confirmation = {
                orderId: response.orderId,
                emailAddress: order.emailAddress,
                printable: response.printable
            };
            // Go to confirmation page
            $rootScope.goTo('/cart/confirmation/' + response.orderId);
            // Store cart
            Cart.store(response.cart);
            // Apply callback
            if (angular.isFunction(success)) success(response);
        }, function (response) {
            $rootScope.$storage.spinner = false;
            Utils.showParsedErrors(response.data.messages, $rootScope.$persistentStorage.device);
            if (angular.isFunction(error)) error(response);
        });
    };

    // Returns true or false if cart is express
    Cart.isExpress = function () {
        return !!($rootScope.$storage.cc);
    };

    // Swipe based checkout
    Cart.expressCheckout = function () {
        if (!Cart.isExpress()) return;
        // Post order to API
        Cart.checkout({
            emailAddress: $rootScope.$storage.user.emailAddress || null,
            cardholderName: $rootScope.$storage.user.fullName || null,
            firstName: $rootScope.$storage.user.firstName || null,
            lastName: $rootScope.$storage.user.lastName || null,
            cardNo: null,
            cardExpiryYear: null,
            cardExpiryMonth: null,
            cardSecurityCode: null,
            password: null,
            address1: $rootScope.$storage.user.address1 || null,
            address2: $rootScope.$storage.user.address2 || null,
            city: $rootScope.$storage.user.city || null,
            stateProvince: $rootScope.$storage.user.stateProvince || null,
            countryId: $rootScope.$storage.user.countryId || null,
            postalCode: $rootScope.$storage.user.postalCode || null,
            paymentKiosk: $rootScope.$storage.cc
        }, function () {
            // Clear cc data from session on success
            Cart.clearCreditCard();
        }, function () {
            // Clear cc data from session on error
            Cart.clearCreditCard();
        });
    };

    // Is order for upgrade, swap or redeem
    Cart.editMode = function () {
        return !!($routeParams.edittype);
    };

    // Is order for events only
    Cart.eventMode = function () {
        return $routeParams.eventMode === 'true';
    };

    /**
    * Clear credit card data from session
    */
    Cart.clearCreditCard = function () {
        // Clear cart from previous session
        $rootScope.$storage.cc = Storage.defaults.session.cc;
    };

    /**
    * Empty Cart
    */
    Cart.empty = function () {
        // Clear cart from previous session
        $rootScope.$storage.cart = Storage.defaults.session.cart;
    };
  
    return Cart;

}]);
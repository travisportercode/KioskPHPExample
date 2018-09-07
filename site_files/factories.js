/*************************** Factories ***************************/

/** 
  * Exhibitions API Factory
  */

/* All exhibitions */
App.factory('Exhibitions', ['$resource', function ($resource) {
    return $resource('/api/exhibition');
}]);

/* Single exhibition by id */
App.factory('Exhibition', ['$resource', function ($resource) {
    return $resource('/api/exhibition/:exhibitionId');
}]);

/* All presentation from a particular exhibition */
App.factory('ExhibitionPresentations', ['$resource', function ($resource) {
    return $resource('/api/exhibition/:exhibitionId/presentations');
}]);

/** 
  * Presentations API Factory
  */

/* All presentations */
App.factory('AllPresentations', ['$resource', function ($resource) {
    return $resource('/api/presentation');
}]);

/* Single presentation by id */
App.factory('Presentation', ['$resource', function ($resource) {
    return $resource('/api/presentation/:defaultPresentationId');
}]);

/** 
  * Tickets API Factory
  */

// All ticket types
App.factory('TicketTypes', ['$resource', function ($resource) {
    return $resource('/api/tickettype');
}]);

// Ticket type details
App.factory('TicketDetails', ['$resource', function ($resource) {
    return $resource('/api/tickettype/:ticketId');
}]);

// Ticket upgrade
App.factory('TicketUpgradeTypes', ['$resource', function ($resource) {
    return $resource('/api/upgrade/tickettype');
}]);

// Ticket edit for upgrade
App.factory('TicketEdit', ['$resource', function ($resource) {
    return $resource('/api/tickettype/:orderTicketType/order/:orderId/ordertickettype/:orderTicketTypeId');
}]);

// Print ticket - get elements
App.factory('TicketElements', ['$resource', function ($resource) {
    return $resource('/api/order/:orderId/ticketselements');
}]);

/* All presentation from a particular exhibition */
App.factory('TicketRecommendations', ['$resource', function ($resource) {
    return $resource('/api/tickettype/recommend/:numberOfPresentations');
}]);


/**
  * Groups API Factory
**/
App.factory('GroupsTransportation', ['$resource', function ($resource) {
    return $resource('api/tickettype/groups/transportation');
}]);

App.factory('GroupsHallsOfFocus', ['$resource', function ($resource) {
    return $resource('api/tickettype/groups/hallsoffocus');
}]);

//Get 'presentations' for arrivalTimes, lunchrooms, and departure times
App.factory('GroupsPresentationsBatch', ['$resource', function ($resource) {
    return $resource('api/tickettype/groups/:presentationid');
}]);

//Get exhibition presentations
App.factory('GroupsExhibitionPresentations', ['$resource', function ($resource) {
    return $resource('api/tickettype/groups/presentations');
}]);


/** 
  * Payment API Factory
  */
App.factory('Payment', ['$resource', function ($resource) {
    return $resource('/api/ordersubmission');
}]);

/** 
  * Order API Factory
  */
App.factory('Order', ['$resource', function ($resource) {
    return $resource('/api/orderlookup/:orderId');
}]);

// Print ticket - post
App.factory('TicketPrint', ['$resource', 'Constants', function ($resource, Constants) {
    return $resource(Constants.printURL, null, {
        send: {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        }
    });
}]);

/** 
  * Cart API Factory
  */

/* Get processed cart */
App.factory('CartGet', ['$resource', function ($resource) {
    return $resource('/api/cart');
}]);

/* Temp cart item */
App.factory('CartItem', ['$resource', function ($resource) {
    return $resource('/api/cart/item', null, {
        update: { method: 'PUT' }
    });
}]);

/* Delete cart item */
App.factory('CartItemDelete', ['$resource', function ($resource) {

    return $resource('/api/cart/item/:itemId');

}]);

/** 
  * Membership Levels API Factory
  */
App.factory('MembershipLevels', ['$resource', function ($resource) {
    return $resource('/api/membershiplevel');
}]);

/** 
  * Memberships API Factory
  */
App.factory('Memberships', ['$resource', function ($resource) {
    return $resource('/api/membershiptype');
}]);

/** 
  * Membership API Factory
  */
App.factory('Membership', ['$resource', function ($resource) {
    return $resource('/api/membershiptype/:membershipId');
}]);

/** 
  * Donation API Factory
  */
App.factory('Donation', ['$resource', function ($resource) {
    return $resource('/api/donationtype/:donationId');
}]);

/** 
  * Verify Membership API Factory
  */
App.factory('MembershipVerify', ['$resource', function ($resource) {
    return $resource('/api/membershipaccount/:memberId');
}]);

/** 
  * Token API Factory
  */
App.factory('Token', ['$resource', function ($resource) {
    return $resource('/token', null, {
        login: {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            }
        }
    });
}]);

/** 
  * Logout API Factory
  */
App.factory('Logout', ['$resource', function ($resource) {
    return $resource('/api/account/logout');
}]);

/** 
  * Register API Factory
  */
App.factory('register', ['$resource', function ($resource) {
    return $resource('/api/membershipaccount/registermember');
}]);

/**
* Renew Membership API Factory
*/
App.factory('RenewMembership', ['$resource', function ($resource) {
    return $resource('/api/membershipaccount/renew', null, {
        update: {
            method: 'PUT'
        }
    });
}]);

/**
* User API Factory
**/
App.factory('UserGet', ['$resource', function($resource) {
    
    return $resource('/api/membershipaccount/info');

}]);

App.factory('NameCollision', ['$resource', function ($resource) {
    return $resource('/api/namecollision');
}]);

/** 
  * Upsell API Factory
  */
App.factory('Upsell', ['$resource', function ($resource) {
    return $resource('/api/upsell', null,
      {
          'update': { method: 'PUT' }
      });
}]);

/** 
  * Workflow API Factory
  */
App.factory('Workflow', ['$resource', function ($resource) {
    return $resource('/api/workflow', null, {
        submit: { method: 'POST', isArray: true }
    });
}]);

/** 
  * Barcode API Factory
  */
App.factory('GetBarcode', ['$resource', function ($resource) {
    return $resource('/barcode');
}]);

/** 
  * Get Country, States API Factory
  */
App.factory('Countries', ['$resource', function ($resource) {
    return $resource('/api/countries');
}]);

App.factory('StatesFromCountry', ['$resource', function ($resource) {
    return $resource('/api/country/:countryId/states');
}]);

/** 
  * Email render API Factory
  */
App.factory('EmailRender', ['$resource', function ($resource) {
    return $resource('/api/order/:orderId/email/:emailAddress/type/:emailType');
}]);

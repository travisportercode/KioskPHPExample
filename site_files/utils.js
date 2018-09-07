/** 
 * Utils Angular Service
 */
App.factory('Utils', ['$window', '$timeout', '$translate', '$route', '$location', '$anchorScroll', 'Constants', 'toaster', function ($window, $timeout, $translate, $route, $location, $anchorScroll, Constants, toaster) {

    var Utils = {
        /**
        * Navigational function to reload routes
        */
        reload: function () {
            $timeout(function () {
                $route.reload();
            }, 500);
        },
        /**
        * Navigational function to reload routes
        */
        refresh: function () {
            $timeout(function () {
                $window.location.reload();
            }, 500);
        },
        /**
        * Navigational function to switch routes
        */
        goTo: function (path, absolute) {
            if (absolute) {
                window.location = path;
            } else {
                $location.path(path);
                $anchorScroll();
            }
        },
        /**
        * Navigational function to go to previous route
        */
        goBack: function () {
            window.history.back();
        },
        /**
        * Clear all Params
        */
        clearParams: function () {
            $location.$$search = {};
            $location.$$compose();
        },
        /**
        * Utility function to reset radio input files in angular
        */
        resetRadios: function (arr, obj, remove) {
            angular.forEach(arr, function (o, i) {
                if (obj != o) o.selected = false;
            });
            if (remove) {
                arr.length = 0;
                arr.push(obj);
            }
        },
        /**
        * Utility function to determine the current environment
        */
        getEnvironment: function() {

            var origin = $window.location.origin;

            if (origin.indexOf(Constants.env.local.signature) !== -1) return Constants.env.local.label;
            else if (origin.indexOf(Constants.env.dev.signature) !== -1) return Constants.env.dev.label;
            else if (origin.indexOf(Constants.env.test.signature) !== -1) return Constants.env.test.label;
            /*If unable to identify environment assume production*/
            else return Constants.env.prod.label

        },
        /**
        * Utility function to retrieve personalization iframe root url
        */
        getIframeRootURL: function () {

            var env = Utils.getEnvironment();

            /*@rocky todo: ultimately Test env should point to Amnh personalization production as should all environments*/
            if (env !== Constants.env.prod.label) return Constants.personalization.env.dev.rootUrl + Constants.personalization.iframePath;
            else return Constants.personalization.env.prod.rootUrl + Constants.personalization.iframePath;

        },
        /**
        * Utility function to check is an object is empty
        */
        isEmptyObj: function (obj) {
            if (obj) return Object.keys(obj).length === 0;
        },
        /**
        * Utility function to toggle checkboxes values in angular
        */
        toggleCheck: function (arr, value) {
            if (arr.indexOf(value) === -1) arr.push(value);
            else arr.splice(arr.indexOf(value), 1);
        },
        /**
        * Get Document URL
        */
        getDocumentURL: function() {
            return document.URL
        },
        /**
       * Get Document URL
       */
        getHostname: function () {
            return location.protocol + '//' + location.host
        },
        /**
        * Regular Expressions for Validation
        */
        stateRegex: /^\w\w$/,
        zipRegex: /^\d\d\d\d\d$/,
        postalCodeRegex: /(^\d{5}$)|(^\d{5}-\d{4}$)/,
        /**
        * Validate Email
        */
        validateEmail: function (email) {
            var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return emailRegex.test(email);
        },
        /**
        * Serialize object to URL string
        */
        serialize: function(obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        },
        /**
        * Parse Card reader data for Track 1
        */
        parseCardData: function (cardData) {

            if (!cardData) return;

            var details1, details2, cardNumber, cardType, memberId, names, firstNames, firstName, lastName, middleInitial, expDate;

            details1 = cardData.split('^');
            cardNumber = details1[0];
            memberId = details1[0];
            names = details1[1].split('/');
            firstNames = names[1].split(' ');
            firstName = firstNames[0].toLowerCase();
            middleInitial = firstNames[1];
            lastName = names[0].trim().toLowerCase();
            details2 = details1[2].split(";");
            details2 = details2[1].split("=");
            expDate = details2[1];

            cardData = {
                memberId: memberId.substring(1),
                ccNumber: cardNumber.substring(1),
                firstName: firstName.charAt(0).toUpperCase() + firstName.slice(1) + ' ' + middleInitial,
                lastName: lastName.charAt(0).toUpperCase() + lastName.slice(1),
                expMonth: expDate.substring(2, 4),
                expYear: expDate.substring(0, 2)
            }

            return cardData;

        },
        showParsedErrors: function (errors, device) {

            if (angular.isArray(errors) && toaster) {

                var parsedErrors = '';

                angular.forEach(errors, function (message, errorIndex) {
                    $translate('ERRORS.' + (device == 'Kiosk' ? 'KIOSK' : 'DESKTOP') + '.' + message.errorCode).then(function (errorTranslated) {
                        parsedErrors += errorTranslated + (errors.length == (errorIndex + 1) ? '' : '<br /><br />');
                        if (errors.length == (errorIndex + 1)) {
                            toaster.pop({
                                type: 'error',
                                body: parsedErrors
                            });
                        }
                    });
                });
                
            } else {

                return errors;

            }

        },
        alert: function (msg) {
            $window.alert(msg);
        },
        today: new Date,
        currentYear: new Date().getFullYear(),
        oneYearFromNow: new Date().setYear(new Date().getFullYear() + 1),
        /**
         * Provides years for select boxes. Use with ngOptions like this: ng-options="code as name for (code, name) in years()"
         * @returns {object} Years for select box
         */
        years: function () {
            var years = {};
            angular.forEach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], function (value, key) {
                years[Utils.currentYear + key] = (Utils.currentYear + key).toString();
            });
            return years;
        },
        /**
         * Provides months for select boxes. Use with ngOptions like this: ng-options="card.code as card.name for card in cards(true)"
         * @param ordered If ordered is array otherwise object
         * @returns {Array|object} if ordered returns array of months otherwise object of months
         */
        months: function (ordered) {
            return Constants.months;
        },
    };

    return Utils;

}]);
/** 
 * User Service
 */

App.service('User', ['$rootScope', '$timeout', '$modal', '$alert', '$http', 'toaster', 'Constants', 'Utils', 'Token', 'Logout', 'NameCollision', 'UserGet', function ($rootScope, $timeout, $modal, $alert, $http, toaster, Constants, Utils, Token, Logout, NameCollision, UserGet) {

    var User = {
        bearerToken: ''
    };

    /**
    * Save user in storage
    */
    User.store = function (user) {
        if (user) user = angular.fromJson(user);
        // Clear personal data from anonymous user
        if (user && user.isAnonymousUser) user.fullName = user.firstName = user.lastName = user.emailAddress = user.postalCode = user.address1 = user.address2 = user.city = user.countryId = '';
        // Store user in storage
        $rootScope.$storage.user = user || null;
    };

    /**
    * Save session in storage
    */
    var storeSession = function (session) {
        $rootScope.$storage.session = session || null;
    };

    /**
    * Login method
    */
    User.login = function (account, success, error) {

        if (!account) return;
        else angular.extend(account, { 'grant_type': 'password', 'clientID': 'BCF799736B0548EBBA22D46A8FB033BD8D76D653355C4E11B3BF20DA4C49AC0E' });
        $timeout(function () {
            $rootScope.$storage.spinner = true;
        });
        Token.login(Utils.serialize(account), function (response) {
            if (!response.user) {
                toaster.pop({
                    type: 'error',
                    body: 'Login failed'
                });
            }
            // Store user
            User.store(response.user);
            storeSession(response);
            $http.defaults.headers.common.Authorization = "Bearer " + response.access_token;
            $rootScope.$storage.spinner = false;
            //Set user to cookie for universal login
            User.setUserToCookie(angular.fromJson(response));
            // Apply callback if provided
            if (angular.isFunction(success)) success(response);
        }, function (response) {
            $rootScope.$storage.spinner = false;
            toaster.pop({
                type: 'error',
                body: 'Login failed'
            });
            // Apply callback if provided
            if (angular.isFunction(error)) error(response);
        });
    };

    /**
    * Login As Anonymous
    */
    User.loginAsAnonymous = function (callback) {
        User.login({ username: '', password: '' }, function () {
            if (angular.isFunction(callback)) callback();
        });
    };

    /**
    * Is user logged in
    */
    User.hasSession = function () {
        return !!(!Utils.isEmptyObj($rootScope.$storage.user));
    };

    /**
    * Logout method
    */
    User.logout = function (success, error) {
        $rootScope.$storage.spinner = true;
        Logout.save(function (response) {
            // Logout on EZ
            $http.jsonp(Constants.personalization.env.dev.rootUrl + Constants.personalization.logout);
            toaster.pop({
                type: 'warning',
                body: 'Logout successfull'
            });
            $http.defaults.headers.common.Authorization = "";
            // Clear user from storage
            User.store();
            //remove user cookies
            User.removeUserCookies();
            $rootScope.$storage.spinner = false;
            // Apply callback if provided
            if (angular.isFunction(success)) success(response);
        }, function (response) {
            $rootScope.$storage.spinner = false;
            toaster.pop({
                type: 'error',
                body: 'Login failed'
            });
            // Apply callback if provided
            if (angular.isFunction(error)) error(response);
        });
    };

    /**
    * Check if email is valid for name collission
    */
    User.isEmailUnique = function (email, isUnique, isNotUnique) {
        NameCollision.get({ emailaddress: email }, function (response) {
            var isUnique = !!(response[0] == 't');
            if (isUnique && angular.isFunction(isUnique)) isUnique();
            else if (!isUnique && angular.isFunction(isNotUnique)) isNotUnique();
        });
    };

    /**
    * Prompt user for sign up
    */
    User.promptForSignUp = function () {
        // Create modal from sign up directive
        var signUpModal = $modal({
            template: '/Content/views/directives/sign-up.html'
        });
    };

    /**
    * Welcome user via notification on login
    */
    User.welcome = function () {
        if ($rootScope.$storage.user && $rootScope.$storage.user.prompt === 'None') {
            $timeout(function () {
                $alert({
                    template: '/Content/views/user/user-welcome-alert.html',
                    animation: 'am-fade-and-slide-top',
                    placement: 'top',
                    type: 'success',
                    duration: 10,
                    show: true
                });
            }, 500);
        }
    };

    /**
    * Alert user via notification on members only action
    */
    User.alertMembersOnly = function () {
        $timeout(function () {
            $alert({
                template: '/Content/views/user/user-members-only-alert.html',
                animation: 'am-fade-and-slide-top',
                placement: 'top',
                type: 'error',
                show: true
            });
        }, 500);
    };

    /**
    * Set user cookie helper
    **/
    User.setUserCookie = function (key, value, options) {
        var _options = angular.extend({ path: Constants.cookiePath, domain: Constants.cookieDomain }, options);
        $.cookie(key, value, _options);
    };

    /**
    * Remove user cookie helper
    **/
    User.removeUserCookie = function (key, options) {
        var _options = angular.extend({ path: Constants.cookiePath, domain: Constants.cookieDomain }, options);
        $.removeCookie(key, _options);
    };

    /**
    * Set user to cookie
    * @rocky todo: rename method after merge is complete
    **/
    User.setUserToCookie = function (response) {
        if (!response) return console.error('user is invalid in setUserToCookie');
        /*Set access_token to local storage as 'authorization' to compare storage to cookie later */
        $rootScope.$storage.authorization = response.access_token;
        /*@ticketing todo: response.access_token should be authorization*/
        User.setUserCookie(Constants.cookieKeys.authorization, response.access_token);
    };

    /**  
    * Set user cookie to storage
    * @rocky todo: rename method after merge is complete
    **/
    User.setUserFromCookie = function (success, error) {

        $rootScope.$storage.spinner = true;
        $rootScope.$storage.authorization = User.getBearerToken();
        $http.defaults.headers.common.Authorization = 'bearer ' + User.getBearerToken();

        UserGet.get(function (response) {
            $rootScope.$storage.spinner = false;
            var user = response;

            if (angular.isUndefined(user)) {
                if (angular.isFunction(error)) error(response);
                return console.error('response.user invalid in UserGet');
            } else {
                User.store(user);
                if ($rootScope.$storage.user.IsAnonymoususer) {
                    console.log('Anonymous user stored from cookie');
                }
                else {
                    console.log('User stored from cookie');
                }

                if (angular.isFunction(success)) success(response);

            }
        }, function (response) {
            $rootScope.$storage.spinner = false;
            if (angular.isFunction(error)) error(response);
            toaster.pop({
                type: 'error',
                body: 'Login failed'
            });
            if (angular.isFunction(error)) error(response);
        });
    };

    /**
    * Check if user cookie already exists
    * @rocky todo: rename method after merge is complete
    **/
    User.isUserCookieSet = function (userObj) {
        return angular.isDefined($.cookie(Constants.cookieKeys.authorization));
    };

    /**
    * Remove user cookies
    * @rocky todo: rename method after merge is complete
    **/
    User.removeUserCookies = function () {
        if ($.cookie(Constants.cookieKeys.authorization)) User.removeUserCookie(Constants.cookieKeys.authorization);
        else return console.warn('Unable to remove cookie ' + Constants.cookieKeys.authorization + ' because it does not exist');
    };

    /**
    * Get Bearer Token
    **/
    User.getBearerToken = function () {
        return $.cookie(Constants.cookieKeys.authorization);
    };

    /**
    * Check user's eligibility for sign up
    */
    User.isElegibleForSignUp = function () {
        return !!($rootScope.breakpoint == 'xl' && $rootScope.$storage.user.isAnonymousUser && !$rootScope.$storage.user.emailAddress && !$rootScope.$storage.user.emailPrompt);
    };

    /**
    * Alert user for membership renewal based on prompt value and kiosk breakpoint
    */
    User.alertMembershipRenewal = function () {
        if ($rootScope.$storage.user && $rootScope.$storage.user.prompt === 'RenewMembership' && $rootScope.breakpoint === 'xl') {
            $alert({ 
                template: '/Content/views/membership/membership-renew-alert.html',
                placement: 'top',
                type: 'warning',
                show: true
            });
        }
    };

    /**
    * Alert user for lapsed membership based on prompt value and kiosk breakpoint
    */
    User.alertMembershipLapsed = function () {
        if ($rootScope.$storage.user && $rootScope.$storage.user.prompt === 'LapsedMembership' && $rootScope.breakpoint === 'xl') {
            $alert({
                template: '/Content/views/membership/membership-lapsed-alert.html',
                placement: 'top',
                type: 'warning',
                duration: 0,
                show: true
            });
        }
    };

    var iframeModal = null;

    /*
    * Show login Modal
    */
    User.showIframeModal = function (template) {
        if (!template) return console.error('template provided to showLoginModal is invalid.');
        iframeModal = $modal({
            scope: $rootScope,
            template: template,
            show: false
        });
        iframeModal.$promise.then(function (result) {
            iframeModal.show();
        }, function (reason) {
            console.error('unable to show modal: ' + reason);
        });
    };

    /*
    * Hide login Modal
    */
    User.hideIframeModal = function () {
        iframeModal.hide();
    };

    return User;

}]);
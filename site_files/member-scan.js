/*************************** Directives ***************************/

/** 
 * Member Scan Angular Directive 
 * Based on 2009-2010 Jonathan Stoppani (http://garetjax.info/)
 */

App.directive('memberScan', ['$rootScope', '$log', '$window', '$timeout', '$route', '$parse', function ($rootScope, $log, $window, $timeout, $route, $parse) {

    // Declare vars
    var error_start = '<'.charCodeAt(0);
    var first_char = 'M';
    var track_start = first_char.charCodeAt(0);
    var track_end = 13;
    var timeout = 100;
    var started = false;
    var finished = false;
    var isError = false;
    var input = '';
    var timer = undefined;
    var callbacks = [];
    var errbacks = [];
    var validators = [];
    var isDispatching = false;
    var focusedElements = null;

    // Member scanner object
    MemberScanner = {

        // On finish scanning
        dispatch: function (data, isError) {

            if (!isError) {
                for (var cb in validators) {
                    if (!validators[cb](data)) {
                        isError = true;
                        break;
                    }
                }
            }

            if (isDispatching) {
                if (isError) {
                    $log.log('Member scan immediate error');
                    return;
                } else $timeout.cancel(isDispatching);
            }

            var scanner = this;

            isDispatching = $timeout(function () {
                scanner.isDispatching = false;
            }, timeout);

            if (isError) {
                $log.log('scan isError');
                for (var cb in errbacks) {
                    errbacks[cb](input);
                }
            } else {
                //$log.log('success', input);
                for (var cb in callbacks) {
                    callbacks[cb](input);
                }
            }
        },

        iterations: 1,

        // On every scan
        observe: function (e) {

            focusedElements = angular.element('input:focus, textarea:focus, select:focus');

            if (focusedElements.length) return;

            if (MemberScanner.iterations < track_end) {
                MemberScanner.iterations++;
                
            } else {
                MemberScanner.iterations = 1;
            }

            $log.log('scan observe e.which = ', e.which);

            var ob = this;

            // On first scan
            if (!started && (e.which === track_start || e.which === error_start)) {
                e.preventDefault();

                MemberScanner.iterations = 0;

                $log.log('scan 1:  started');

                input += String.fromCharCode(e.which);

                started = true;
                isError = e.which === error_start;

                timer = $timeout(function () {
                    ob.started = false;
                    ob.finished = false;
                    ob.isError = false;
                    ob.input = '';
                }, timeout);

                
            }

            // On last scan
            else if (started && e.which === track_end) {
                e.preventDefault();

                $log.log('scan 2');

                finished = true;

                MemberScanner.dispatch(input, isError);

                started = false;
                finished = false;
                isError = false;
                input = '';

                $timeout.cancel(timer);
                timer = $timeout(function () {
                    ob.started = false;
                    ob.finished = false;
                    ob.isError = false;
                    ob.input = '';
                }, timeout);

                $timeout.cancel(timer);

                
            }

            // On every read but first
            else if (started && !focusedElements.length) {
                e.preventDefault();

                $timeout(function () {
                    $rootScope.$storage.spinner = true;
                });

                $log.log('scan 3');

                input += String.fromCharCode(e.which);

                $timeout.cancel(timer);
                timer = setTimeout(function () {
                    ob.started = false;
                    ob.finished = false;
                    ob.isError = false;
                    ob.input = '';
                }, timeout);
            }

            // on abort
            else if ((!started && e.which === error_start) || focusedElements.length) {
                $timeout(function () {
                    $rootScope.$storage.spinner = false;
                });
            }

        },

        // Remove M from member id
        parse: function (output) {
            if (!output) return;
            else return output.replace(first_char, '');
        },

        // Detroy method
        destroy: function (element) {
            if (element) element.unbind('keypress');
        },

        // On success
        onSuccess: function (callback) {
            callbacks.push(callback);
        },

        // On error
        onError: function (errback) {
            errbacks.push(errback);
        }

    };

    return {
        restrict: 'A',
        link: function (scope, element, attrs) {

            // Bind event to window object based on route scan boolean value
            element.keypress(function (e) {
                if ($route.current.$$route.scan) MemberScanner.observe.apply(MemberScanner, arguments);
            });

            // Apply function in scope if provided
            MemberScanner.onSuccess(function (output) {
                // Parse function for external callback
                var scanCallback = $parse(attrs.memberScan);
                $timeout(function () {
                    $rootScope.$storage.spinner = false;
                    $log.log('Member scan success: ' + output);
                });
                scanCallback(scope, { memberId: output });
            });

            // Apply on error function in scope if provided
            MemberScanner.onError(function () {
                $timeout(function () {
                    $rootScope.$storage.spinner = false;
                    $log.log('Member scan error');
                });
            });

        }
    }

}]);
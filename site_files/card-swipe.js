/*************************** Directives ***************************/

/** 
 * Card Reader Angular Directive 
 * Based on 2009-2010 Jonathan Stoppani (http://garetjax.info/)
 */

App.directive('cardSwipe', ['$rootScope', 'toaster', '$log', '$window', '$timeout', '$route', '$parse', function ($rootScope, toaster, $log, $window, $timeout, $route, $parse) {

    // Declare vars
    var error_start = 'M'.charCodeAt(0);
    var track_start = '<'.charCodeAt(0);
    var track_end = '>'.charCodeAt(0);
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

    // Card reader object
    CardReader = {

        // On finish reading
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
                    $log.log('Card swipe immediate error');
                    return;
                } else $timeout.cancel(isDispatching);
            }

            var reader = this;

            isDispatching = $timeout(function () {
                reader.isDispatching = false;
            }, timeout);

            if (isError) {
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

        // On every read
        observe: function (e) {

            focusedElements = angular.element('input:focus, textarea:focus, select:focus');

            if (focusedElements.length) return;

            $log.log('swipe observe: ' + e.which);

            var ob = this;

            // On first read
            if (!started && (e.which === track_start || e.which === error_start)) {
                e.preventDefault();

                $log.log('swipe started');

                input += String.fromCharCode(e.which);

                started = true;
                isError = e.which === error_start;

                if (isError) {
                    $timeout(function () {
                        $rootScope.$storage.spinner = false;
                    });
                }

                timer = $timeout(function () {
                    ob.started = false;
                    ob.finished = false;
                    ob.isError = false;
                    ob.input = '';
                }, timeout);

            
            }

            // Before last read
            else if (started && e.which === track_end) {
                e.preventDefault();

                $timeout(function () {
                    $rootScope.$storage.spinner = true;
                });

                input += String.fromCharCode(e.which);

                finished = true;

                $timeout.cancel(timer);
                timer = $timeout(function () {
                    ob.started = false;
                    ob.finished = false;
                    ob.isError = false;
                    ob.input = '';
                }, timeout);

            
            }

            // On last read
            else if (started && finished && e.which === 13) {
                e.preventDefault();

                CardReader.dispatch(input, isError);

                started = false;
                finished = false;
                isError = false;
                input = '';

                $timeout.cancel(timer);

            
            }

            // On every read but first
            else if (started && !focusedElements.length) {
                e.preventDefault();

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

        // XML to obj
        parse: function (output) {
            if (!output) return;

            var parser = new DOMParser();
            var xml = parser.parseFromString(output, 'text/xml');

            return angular.copy({
                cardholderName: angular.element(xml).find('Card').attr('CHolder') || '',
                cardData: angular.element(xml).find('Card').attr('ETrk2') || '',
                cardExp: angular.element(xml).find('Card').attr('Exp') || '',
                tranType: angular.element(xml).find('Tran').attr('TranType') || '',
                deviceKSN: angular.element(xml).find('Card').attr('CDataKSN') || ''
            });
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
        scope: true,
        link: function (scope, element, attrs) {

            // Bind event to window object based on route scan boolean value
            element.keypress(function (e) {
                if ($route.current.$$route.swipe) CardReader.observe.apply(CardReader, arguments);
            });

            // Apply function in scope if provided
            CardReader.onSuccess(function (output) {
                // Parse function for external callback
                var swipeCallback = $parse(attrs.cardSwipe);
                var card = CardReader.parse(output);
                // Store cc
                $timeout(function () {
                    $rootScope.$storage.spinner = false;
                    $log.log('Card swipe success: ', output, card);
                    // Store card in session
                    if (card.cardData && card.cardholderName) {
                        // Notify the user that card was read
                        toaster.pop({
                            type: 'success',
                            body: 'Credit Card was read'
                        });
                        // Store cc
                        $rootScope.$storage.cc = card;
                        // Apply callback with card as argument
                        swipeCallback(scope, { card: CardReader.parse(output) });
                    } else {
                        // Notify the user that card was read
                        toaster.pop({
                            type: 'error',
                            body: 'Could not read Credit Card. Please try again.'
                        });
                    }
                });
                
            });

            // Apply on error function in scope if provided
            CardReader.onError(function () {
                $timeout(function () {
                    $rootScope.$storage.spinner = false;
                    $log.log('Card swipe error');
                });
            });

        }
    }

}]);
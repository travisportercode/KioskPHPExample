/*************************** Directives ***************************/

/** 
  * Modal Iframe
  */
App.directive('modalIframe', ['$rootScope', '$window', '$interval', '$sce', 'User', 'Utils', function ($rootScope, $window, $interval, $sce, User, Utils) {
    return {
        templateUrl: '/Content/views/directives/iframe-modal.html',
        restrict: 'E',
        replace: true,
        scope: {
            path: '@'
        },
        link: function (scope, element, attrs) {
            
            //Set defaults
            var _modal = null;
            var _iframe = element.get(0);
            var _postMessage = false;
            var _iframeLoaded = false;
            var _iframeClosed = false;
            var _statusInterval = null;
            var _rootUrl = Utils.getIframeRootURL();
            var _iframeMessages = {
                size: 'size',
                userCookieSet: 'cookieset',
                complete: 'complete'
            }

            //scope.iframeUrl = Utils.rootUrl + path;
            scope.iframeUrl = $sce.trustAsResourceUrl(_rootUrl + scope.path);

            $rootScope.$storage.spinner = true;

            //Send post message to the iframe
            scope.sendPostMessage = function (msg) {
                if (!msg) return console.error('msg passed to sendPostMessage is invalid');
                if (!_iframe.contentWindow) return;
                _iframe.contentWindow.postMessage(msg, '*');
            };

            scope.closeModalIframe = function () {
                //Else if message type is complete then close the modal
                User.hideIframeModal();
                //and cancel the status interval
                $interval.cancel(_statusInterval);
                //Set the iframe open state to closed
                _iframeClosed = true;
            };

            //Handle message response from iframe
            scope.handleResponse = function (e) {
                if (!e) return console.error('e invalid in handleSizingResponse');
                //If the iframe close process has begun, exit
                if (_iframeClosed) return;

                var msg = e.data;
                var msgType = '';

                //If no pipe we have a single data message str
                if (msg.indexOf('|') == -1) {
                    msgType = e.data;
                } else {
                    //Else we are expecting one or more data points
                    msg = e.data.split('|');
                    msgType = msg[0];
                }

                //If msgType is size then get the width and height
                if (msgType == _iframeMessages.size) {
                    var iframeWidth = msg[1];
                    var iframeHeight = msg[2];
                    _iframe.style.height = iframeHeight + 'px';
                } else if (msgType == _iframeMessages.userCookieSet) {
                    //Close and stop operations
                    scope.closeModalIframe();
                    //Set User from cookie
                    User.setUserFromCookie();
                } else if (msgType == _iframeMessages.complete) {
                    //Close and stop operations
                    scope.closeModalIframe();
                }
            };

            //Poll the iframe status to find out if we can stop polling the iframes for messages
            scope.checkIframeStatus = function () {
                //If an iframe window exists
                if (_iframe.contentWindow) {
                    //Send message to get the status of the iframe
                    scope.sendPostMessage('getStatus');
                } else {
                    //If there is no longer an iframe.contentWindow
                    //the modal has been closed, so cancel the status interval
                    $interval.cancel(_statusInterval);
                }
            };

            //Once the iframe is loaded...
            scope.setIframeLoaded = function () {
                //If iframe has already been opened return
                if (_iframeLoaded) {
                    //Get potential new page size
                    scope.sendPostMessage('getSizing');
                    //Exit
                    return false;
                }
                //Iframe loaded
                _iframeLoaded = true;
                //Turn the spinner off
                $rootScope.$storage.spinner = false;
                //Begin listening for messages from the iframe
                window.addEventListener('message', scope.handleResponse, false);
                //Send a post message to the iframe
                scope.sendPostMessage('getSizing');
                //On window resize request iframe dimensions via postMessage
                window.onresize = function () {
                    scope.sendPostMessage('getSizing');
                };
                //Set an interval to check the status of the iframe process
                _statusInterval = $interval(scope.checkIframeStatus, 60);

            };

            //On iframe load call setIframeLoaded
            _iframe.addEventListener('load', scope.setIframeLoaded);

        }
    }

}]);
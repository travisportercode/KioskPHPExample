/*************************** Directives ***************************/

/** 
  * Main header
  */
App.directive('mainHeader', [function () {
    return {
        templateUrl: '/Content/views/directives/main-header.html',
        restrict: 'E',
        replace: true
    }
}]);

/** 
  * Cart sidebar
  */
App.directive('cartSidebar', [function () {
    return {
        templateUrl: '/Content/views/cart/cart-sidebar.html',
        restrict: 'E',
        replace: true
    }
}]);

/** 
  * Dev Mode
  */
App.directive('devMode', [function () {
    return {
        templateUrl: '/Content/views/directives/dev-mode.html',
        restrict: 'E',
        replace: true
    }
}]);

/** 
  * Breakpoint
  */
App.directive('breakpoint', ['$rootScope', '$window', '$timeout', function ($rootScope, $window, $timeout) {
    return {
        restrict: 'A',
        link: function (scope, element) {

            $rootScope.breakpoint = null;

            scope.updateBreakpoint = function () {
                if ($window.innerWidth <= 480)
                    $rootScope.breakpoint = 'xs';
                else if ($window.innerWidth > 480 && $window.innerWidth <= 768)
                    $rootScope.breakpoint = 'sm';
                else if ($window.innerWidth > 768 && $window.innerWidth <= 992)
                    $rootScope.breakpoint = 'md';
                else if ($window.innerWidth > 992 && $window.innerWidth <= 1200)
                    $rootScope.breakpoint = 'lg';
                else $rootScope.breakpoint = 'xl';
            };

            angular.element($window).bind('resize', function () {
                scope.$apply(function () {
                    scope.updateBreakpoint();
                });
            });

            scope.updateBreakpoint();

            $timeout(function () {
                $(window).trigger('resize');
            }, 1200);

        }
    }
}]);

/** 
  * Screen
  */
App.directive('screen', ['$window', '$timeout', function ($window, $timeout) {
    return function (scope, element) {

        scope.screen = {};

        function applyScopeVars() {
            scope.screen.width = $window.innerWidth;
            scope.screen.height = $window.innerHeight;
        }

        angular.element($window).bind('resize', function () {
            scope.$apply(function () {
                applyScopeVars();
            });
        });

        applyScopeVars();

        $timeout(function () {
            $(window).trigger('resize');
        }, 1200)
    }
}]);

/** 
  * Slide and Push Menus doc: http://tympanus.net/codrops/2013/04/17/slide-and-push-menus/
  */
App.directive('slidePush', function () {
    return {
        scope: {
            show: '='
        },
        restrict: 'EA',
        link: function (scope, element, attrs) {

            // Init slide push
            element.addClass('cbp-spmenu');

            // Assign orientation | vertical or horizontal
            if (attrs.orientation) element.addClass('cbp-spmenu-' + attrs.orientation);

            // Assign direction | left, right, top or bottom
            if (attrs.from) element.addClass('cbp-spmenu-' + attrs.from);

            // Prep body for push
            angular.element('body').addClass('cbp-spmenu-push');

            // Toggle menu based on isolated scope atrtibute
            scope.$watch('show', function (show) {
                if (show) {
                    // Open menu
                    element.addClass('cbp-spmenu-open');
                    // Push
                    if (attrs.push === 'true' && attrs.from) angular.element('body').addClass('cbp-spmenu-push-from' + attrs.from);
                    // Disable UI click events
                    if (attrs.disableOther == 'true') angular.element('body').addClass('cbp-spmenu-disabled');
                } else {
                    // Dismiss slide push 
                    scope.dismiss();
                }
            }, true);

            // Dismiss slide push  if user navigates away
            scope.dismiss = function () {
                // Reverse push
                if (attrs.push === 'true' && attrs.from) angular.element('body').removeClass('cbp-spmenu-push-from' + attrs.from);
                // Close menu
                element.removeClass('cbp-spmenu-open');
                // Reverse disable UI click events
                angular.element('body').removeClass('cbp-spmenu-disabled');
            };

            // Dismiss slide push if user navigates away
            scope.$on('$destroy', function () {
                scope.dismiss();
            });

        }
    }

});

/** 
  * Slideshow
  */
App.directive('slideshow', ['$timeout', '$filter', function ($timeout, $filter) {
    return {
        templateUrl: '/Content/views/directives/slideshow.html',
        restrict: 'E',
        replace: true,
        scope: {
            slides: '='
        },
        link: function (scope, element, attrs) {

            if (!scope.slides) return;
            // Options
            scope.showControls = !!(attrs.showControls === 'true');
            scope.duration = parseInt(attrs.duration) || 3000;
            scope.imageProperty = attrs.imageProperty || 'image';
            scope.filter = scope.$eval(attrs.filter) || {};
            scope.orderBy = scope.$eval(attrs.orderBy) || '';
            scope.currentIndex = (scope.slides.length == 1) ? 0 : 1;

            // Next slide func
            scope.next = function () {
                scope.currentIndex < scope.slides.length - 1 ? scope.currentIndex++ : scope.currentIndex = (scope.slides.length == 1) ? 0 : 1;
            };

            // Previous slide func
            scope.prev = function () {
                scope.currentIndex > 0 ? scope.currentIndex-- : scope.currentIndex = scope.slides.length - 1;
            };

            // Reset visibility
            scope.$watch('currentIndex', function (newVal, oldVal) {
                angular.forEach(scope.slides, function (slide, key) {
                    if (scope.currentIndex !== key) slide.visible = false;
                });
                scope.slides[scope.currentIndex].visible = true;
            });

            // Automatic slideshow
            var timer,
                slideshow = function () {
                timer = $timeout(function () {
                    scope.next();
                    timer = $timeout(slideshow, scope.duration);
                }, scope.duration);
            };

            // Init slideshow
            slideshow();

            // Cancel timeout
            scope.$on('$destroy', function () {
                $timeout.cancel(timer);
            });

        }
    }
}]);


/** 
  * Scroll start right
  */
App.directive('scrollStartRight', ['$timeout', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element) {

            element.css({ visibility: 'hidden' });

            $timeout(function () {
                $(element).scrollLeft($(element).width());
                element.css({ visibility: 'visible' });
            }, 0);

        }
    }
}]);

/** 
  * Tickets header
  */
App.directive('headerTickets', [function () {
    return {
        templateUrl: '/Content/views/directives/tickets-header.html',
        restrict: 'E',
        replace: true
    }
}]);

/** 
  * Scan Member card
  */
App.directive('scanMemberCard', [function () {
    return {
        templateUrl: '/Content/views/directives/scan-member-card.html',
        restrict: 'E',
        scope: {
            text: '@',
            color: '@',
            layout: '@',
            size: '@',
            width: '@',
            animate: '=',
        }
    }
}]);

/** 
  * Item Card
  */
App.directive('cardItem', [function () {
    return {
        templateUrl: '/Content/views/directives/card-item.html',
        restrict: 'E',
        scope: {
            heading: '=',
            description: '=',
            membersOnly: '=',
            button: '@',
            width: '@',
            height: '@'
        }
    }
}]);

/** 
  * Background Image
  */
App.directive('backgroundImg', [function () {
    return {
        templateUrl: '/Content/views/directives/background-img.html',
        restrict: 'E',
        replace: true,
        scope: {
            src: '=',
            logo: '=',
            vignette: '='
        }
    }
}]);
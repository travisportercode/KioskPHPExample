/** 
  * Exhibitions
  */
App.directive('scrollspy', ['$rootScope', '$window', '$document', '$log', '$timeout', function ($rootScope, $window, $document, $log, $timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {

            var resizeEvent = null;
            var scrollEvent = null;

            scope.watch = attrs.watch || "";
            scope.watchCollection = attrs.watchCollection || "[]";
            scope.windowEl = angular.element($window);
            scope.docEl = angular.element($document.prop('documentElement'));
            scope.bodyEl = angular.element($window.document.body);
            scope.scrollEl = angular.element(attrs.scrollEl) || scope.windowEl;
            scope.targetAttr = attrs.targetAttr || 'data-target';
            scope.context = (attrs.context) ? angular.element(attrs.context) : angular.element('html, body');
            scope.activeClass = attrs.activeClass || 'active';
            scope.currentIndex = 0;

            scope.pointers = [];
            scope.targets = [];

            //Initialize
            var init = function () {
                config();
                scope.updateOffsets();
                bindResize();
                bindScroll();
                if (scope.watchCollection !== "[]") watchCollection();
                if (scope.watch !== "") watch();
            };

            var config = function () {
                scope.context = angular.element(scope.context);
                scope.pointers = getPointerEls();
                scope.targets = getTargets();
                scope.updateOffsets();
            };
            
            var getPointerEls = function () {
                var pointers = angular.element(element).find('[' + scope.targetAttr + ']');
                return pointers;
            };

            //Match number of targets to number of pointers
            var trimTargets = function () {
                var diff = scope.targets.length - scope.pointers.length;
                if (diff > 0) scope.targets.splice(scope.targets.length - (diff - 1), diff);
            };

            var bindResize = function () {
                resizeEvent = scope.windowEl.on('resize', function () {
                    scope.updateOffsets();
                    scope.checkPosition();
                });
            };

            var bindScroll = function () {
                scrollEvent = scope.windowEl.bind('scroll', function () {
                    scope.checkPosition();
                });
            };

            var getTargets = function () {
                var targets = [];
                angular.forEach(scope.pointers, function (pointer) {
                    var targetRef = null;
                    var target = null;
                    targetRef = angular.element(pointer).attr(scope.targetAttr);
                    target = scope.context.find(targetRef);
                    if (target.length !== 1) return;
                    targets.push({ el: target, offset: 0 });
                });

                return targets;
            };

            var getScrollTop = function () {
                return $window.pageYOffset || scope.scrollEl.prop('scrollTop') || 0;
            };

            var updateCurrentIndex = function (index) {

                if (index < 0 || index > scope.targets.lenth - 1) return $log.error('currentIndex is invalid in update index');
                //Remove old index if necessary
                if (scope.currentIndex != index) removeActive(scope.currentIndex);
                //Add active to current index
                addActive(index);
                //Update scope index
                scope.currentIndex = index;
            };

            var removeActive = function (index) {
                //If not a valid index, get out
                if (index < 0 || index > scope.targets.length - 1) return $log.error('index is invalid in removeActive');
                //Add active class
                angular.element(scope.pointers[index]).removeClass(scope.activeClass);
            };

            var addActive = function (index) {
                //If not a valid index, get out
                if (index < 0 || index > scope.targets.length - 1) return $log.error('index is invalid in addActive');
                angular.element(scope.pointers[index]).addClass(scope.activeClass);
            };

            //Watch objects and refresh on change
            var watch = function () {
                scope.$watch(scope.watch, function () {
                    $timeout(scope.refresh, 100);
                });
            };

            //Watch collection and refresh on change
            var watchCollection = function () {
                console.log('watchCollection: ', scope.watchCollection)
                scope.$watchCollection(scope.watchCollection, function () {
                    $timeout(scope.refresh, 100);
                });
            };

            scope.checkPosition = function () {
                var scrollTop = getScrollTop();
                var currentIndex = -1;
                angular.forEach(scope.targets, function (target, index) {
                    var isLast = (index === scope.targets.length - 1) ? true : false;
                    if (scrollTop >= target.offset) {
                        if (isLast) {
                            currentIndex = index;
                        }
                        else if (scrollTop < scope.targets[index + 1].offset) {
                            currentIndex = index;
                        }
                    }
                });
                updateCurrentIndex(currentIndex);
            };

            scope.updateOffsets = function () {
                var scrollTop = $window.pageYOffset || scope.scrollEl.prop('scrollTop') || 0;

                angular.forEach(scope.targets, function (target, index) {
                    target.offset = angular.element(target.el).prop('offsetTop');
                });
            };

            scope.refresh = function () {
                scope.pointers = getPointerEls();
                scope.targets = getTargets();
                trimTargets();
                scope.updateOffsets();
            };

            //Let DOM print then go
            $timeout(function () {
                init();
            }, 1);

        }
    }
}]);
/** 
  * Datepicker
  */
App.directive('datepicker', ['$rootScope', '$window', 'Utils', function ($rootScope, $window, Utils) {
    return {
        templateUrl: '/Content/views/directives/datepicker.html',
        restrict: 'E',
        require: 'ngModel',
        scope: {
            date: '=ngModel'
        },
        link: function (scope, element, attrs) {
            // Options
            scope.showLabel = !!(attrs.showLabel === 'true');
            scope.showDescription = !!(attrs.showDescription === 'true');
            scope.minDate = attrs.minDate || 'today';
            scope.maxDate = attrs.maxDate || null;
            scope.noStartDate = !!(attrs.noStartDate === 'true');

            // Share screen scope with this directive
            scope.screen = scope.$parent.screen;

            // Set default date to today's date
            if (!scope.date && !scope.noStartDate) scope.date = Utils.today;

            // Broadcast on date change
            scope.$watch('date', function (newDate, oldDate) {
                if (!newDate) {
                    newDate = oldDate;
                    oldDate = null;
                }
                if (newDate !== oldDate && (oldDate || scope.noStartDate)) $rootScope.$broadcast('datepickerChange', newDate);
            });

            // Is selected date today
            $rootScope.isToday = function () {
                var today = new Date();
                var date = new Date(scope.date);
                return !!(today > date);
            };

            // Hide content on blur
            angular.element($window.document.body).on('click', function (e) {
                if (!element.has(angular.element(e.target)).length && angular.element('.datepicker').length) {
                    element.find('[bs-datepicker]').click();
                }
            });

        }
    }

}]);
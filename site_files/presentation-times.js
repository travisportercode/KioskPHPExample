/*************************** Directives ***************************/

/** 
  * Presentation times
  */
App.directive('presentationTimes', ['$rootScope', 'filterFilter', 'Cart', 'Itinerary', function ($rootScope, filterFilter, Cart, Itinerary) {
    return {
        templateUrl: '/Content/views/directives/presentation-times.html',
        scope: {
            exhibition: '=',
            exhibitions: '=',
            singlePresentation: '='
        },
        restrict: 'E',
        link: function (scope, element, attrs) {

            // Add size attribute to scope
            scope.size = (attrs.size ? attrs.size : 'sm');

            // Add limit attribute to scope
            scope.limit = (attrs.limit ? attrs.limit : 999);

            // Add resetRadios to scope
            scope.resetRadios = $rootScope.resetRadios;

            // Add $storage to scope
            scope.$storage = $rootScope.$storage;
            
            // Highlight presentation from itinerary in storage on page reload
            $rootScope.$on('presentationsReady', function () {
                angular.forEach(scope.exhibition.presentations, function (presentation, key) {

                    // Select based on itinerary
                    var selectedPresentation = filterFilter($rootScope.$storage.itinerary, { id: presentation.performanceId, zoneId: presentation.zoneId })[0];
                    if (selectedPresentation) {
                        scope.exhibition.presentations[key].selected = true;
                        scope.presentation = scope.exhibition.presentations[key];
                    }
                });
            });
            
            // Update itinerary handler
            scope.updateItinerary = function (exhibition, presentation) {
                Itinerary.update(exhibition, presentation, scope.exhibitions, scope.singlePresentation);
            };

            // Return true or false if current presentation is available based on amount of remainingSeats
            scope.isPresentationAvailable = function (remainingSeats) {
                return !!(remainingSeats < Cart.getTotalTicketQtys());
            };

            // Unselect presentation based on presentation.selected
            scope.tryUnselect = function (exhibition, presentation) {
                if (!presentation.selected) return;
                presentation.selected = false;
                angular.forEach($rootScope.$storage.itinerary, function (value, key) {
                    if (value.id == presentation.performanceId) $rootScope.$storage.itinerary.splice(key, 1);
                });
            };

            // Add to itinerary based on selected
            angular.forEach(scope.exhibition.presentations || scope.presentations, function (presentation, key) {
                if (presentation.selected) scope.updateItinerary(scope.exhibition, presentation);
            });

        }
    }

}]);
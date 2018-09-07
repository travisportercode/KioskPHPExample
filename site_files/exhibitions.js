/** 
  * Exhibitions
  */
App.directive('exhibitions', [function () {
    return {
        templateUrl: '/Content/views/directives/exhibitions.html',
        restrict: 'E',
        scope: {
            exhibitions: '=',
            breakpoint: '=',
            ticket: '='
        }
    }
}]);
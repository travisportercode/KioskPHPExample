/*************************** Directives ***************************/

/** 
  * Sign-up
  */
App.directive('signUp', ['$rootScope', '$timeout', function ($rootScope, $timeout) {
    return {
        restrict: 'C',
        replace: true,
        link: function (scope, element, attrs) {

            // Options
            scope.closeAfter = attrs.closeAfter || 4000;

            // Init
            scope.step = 1;
            
            // Close modal
            scope.dismiss = function () {
                // Hide modal
                scope.$hide();
                // Keep track of prompt
                $rootScope.$storage.user.emailPrompt = true;
                // Continue to next step
                $rootScope.continue();
            };

            // Save email on storage
            scope.submit = function () {
                // Keep track of prompt
                $rootScope.$storage.user.emailPrompt = true;
                // Store user's email in session storage
                $rootScope.$storage.user.emailAddress = scope.email;
                // Show next step
                scope.step = 2;
                // Close modal after a few seconds
                // Timer
                var timer = $timeout(function () {
                    scope.dismiss();
                }, scope.closeAfter);
                // Cancel timer on destroy
                scope.$on('modal.hide', function (event) {
                    $timeout.cancel(timer);
                });
            };

        }
    }

}]);
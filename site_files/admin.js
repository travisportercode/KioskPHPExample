/*************************** Admin Controller ***************************/

App.controller('adminCtrl', ['$scope', '$rootScope', 'filterFilter', 'Constants', 'toaster', 'Exhibitions', function ($scope, $rootScope, filterFilter, Constants, toaster, Exhibitions) {

    Exhibitions.query({ eventType: 'SpecialEvent' }, function (events) {

        $scope.fullEvents = events;
        
        $scope.events = {};
        
        angular.forEach(events, function (event) {
            $scope.events[event.id] = event.name;
        });
    });

    $scope.admin = angular.copy($rootScope.$persistentStorage);

    $scope.devices = Constants.devices;

    $scope.setMode = function () {

        if ($scope.admin.code != Constants.admin.password) {
            toaster.pop({
                type: 'error',
                body: 'Wrong password'
            });
            return;
        }

        delete $scope.admin.code;

        $rootScope.$persistentStorage.device = $scope.admin.device;
        if ($scope.admin.eventMode) $rootScope.$persistentStorage.eventMode = $scope.admin.eventMode;
        if ($scope.admin.eventId) $rootScope.$persistentStorage.eventId = $scope.admin.eventId;
        if ($scope.admin.eventId) $rootScope.$persistentStorage.event = angular.copy(filterFilter($scope.fullEvents, { id: $scope.admin.eventId })[0]);

        if ($scope.admin.devMode) {
            $rootScope.$persistentStorage.devMode = $scope.admin.devMode;
            $rootScope.$persistentStorage.testPayment = Constants.test.payment;
            $rootScope.$persistentStorage.testUser = Constants.test.user;
        }

        toastr.success('Device set to ' + $rootScope.$persistentStorage.device + '<br /> Event Mode set to ' + $rootScope.$persistentStorage.eventMode + '<br /> Event is ' + $scope.events[$rootScope.$persistentStorage.eventId]);

        $rootScope.goTo('/welcome');

    }

}]);
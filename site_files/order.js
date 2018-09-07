/*************************** Order Controller ***************************/

App.controller('orderCtrl', ['$scope', '$rootScope', '$location', '$routeParams', '$sce', 'Order', 'EmailRender', 'CardReader', 'Utils', function ($scope, $rootScope, $location, $routeParams, $sce, Order, EmailRender, CardReader, Utils) {

    // Get order details for emailRender
    if ($routeParams.emailAddress && $routeParams.orderId) {

        // Get email html
        EmailRender.get({ emailAddress: decodeURI($routeParams.emailAddress), orderId: $routeParams.orderId, emailType: $routeParams.emailType }, function (order) {
            $scope.emailRender = $sce.trustAsHtml(order.orderEmail.bodyParts[0].body);
        });

    }

}]);
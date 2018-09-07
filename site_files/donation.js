/*************************** Donation Controller ***************************/

App.controller('donationCtrl', ['$scope', '$routeParams', 'Donation', 'Cart', function ($scope, $routeParams, Donation, Cart) {

    // initialize donor object and set default of donateType
    $scope.donor = {};
    $scope.donor.causeType = 'null';
    $scope.donor.basePrice = '';

    // Title options for the donation form
    $scope.titles = ['Mr.', 'Mrs.', 'Ms.'];

    // Donation basePrices model.  TODO: replace with tesitura api
    $scope.basePrices = [500, 250, 100, 50];

    // Get states from US
    $scope.states = [];
    Cart.getStates(1, function (states) {
        $scope.states = states;
    });

    /**
     * Donation Detail Obj
     */
    if ($routeParams.donationId) {
        Donation.get({ donationId: $routeParams.donationId }, function (donation) {
            $scope.donation = donation;
        });
    }

}]);

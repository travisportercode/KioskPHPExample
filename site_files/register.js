/*************************** Membership Controller ***************************/

App.controller('register', ['$scope', '$rootScope', '$routeParams', 'Memberships', 'Membership', 'register', 'filterFilter', function ($scope, $rootScope, $routeParams, Memberships, Membership, register, filterFilter) {

    /**
    * Member Register 
    */
   
    $scope.membershipRegisterSubmit = function () {
        $rootScope.$storage.spinner = true;

        var a = 1;
        var postData = JSON.stringify({
            emailAddress: $scope.membershipRegister.emailAddress,
            firstName: $scope.membershipRegister.firstName,
            lastName: $scope.membershipRegister.lastName,
            PasswordHash: membershipRegisterForm.PasswordHash.value,
            address1: $scope.membershipRegister.address1,
            address2: $scope.membershipRegister.address2 || '',
            city: $scope.membershipRegister.city,
            stateProvince: ($scope.membershipRegister.state == '' ? { stateCode: null, name: '' } : $scope.membershipRegister.state),
            countryId: $scope.membershipRegister.country,
            postalCode: $scope.membershipRegister.postalCode,
        });

        register.save(postData, function (response) {
            $rootScope.$storage.spinner = false;
        });
    };

}]);

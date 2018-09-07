/*************************** Style Guide Controller ***************************/

App.controller('styleGuideCtrl', ['$scope', '$rootScope', '$sessionStorage', 'Exhibitions', 'Presentations', function ($scope, $rootScope, $sessionStorage, Exhibitions, Presentations) {

    //Using Exhibitions and Presentaiton API for "Presentation Buttons" section of style guide.

    // Query Exhibitions
    Exhibitions.query(function (exhibitions) {

        // Add exhibitions to scope
        $scope.exhibitions = exhibitions;

        // Query presentations
        Presentations.query(function (presentations) {

            // Add selected property to each presentation
            angular.forEach(presentations, function (presentation) {
                presentation.selected = false;
            });

            // Attach presentation to their exhibition based on id
            angular.forEach($scope.exhibitions, function (exhibition, key) {
                var presentationsubset = $.grep(presentations, function (presentation) {
                    return (presentation.exhibitionId === exhibition.id);
                });
                $scope.exhibitions[key].presentations = presentationsubset;
            });

        });

        $scope.exhibition = $scope.exhibitions[0];

    });

    //Object for select list

    $scope.select = [
        { name: 1 },
        { name: 2 },
        { name: 3 },
        { name: 4 },
        { name: 5 },
    ]

 }]);
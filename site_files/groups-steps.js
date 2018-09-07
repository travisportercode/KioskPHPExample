/*************************** Directives ***************************/

/** 
  * Groups Nav
  */
App.directive('groupsNav', [function () {
    return {
        templateUrl: '/Content/views/groups/groups-nav.html',
        restrict: 'E',
        replace: true,
        link: function (scope, element, attrs) {

            scope.offsetTopEl = attrs.offsetTopEl || null;
            scope.offsetBottomEl = attrs.offsetBottomEl || null;
            scope.offsetTop = (scope.offsetTopEl) ? angular.element(scope.offsetTopEl).height() : (attrs.offsetTop || 0);
            scope.offsetBottom = (scope.offsetBottomEl) ? angular.element(scope.offsetBottomEl).height() : (attrs.offsetBottom || 0);

            angular.element(element).affix({
                offset: {
                    top: scope.offsetTop,
                    bottom: scope.offsetBottom
                }
            });
        }
    }
}]);

/** 
  * Groups Steps
  */
App.directive('groupsTripDate', [function () {
    return {
        restrict: 'E',
        templateUrl: 'Content/views/groups/groups-trip-date.html',
        controller: function ($scope, $rootScope, Utils, Presentations, Tickets, Cart, Groups, Constants) {

            //Initialize
            $scope.init = function () {
                // One year from now
                $scope.oneYearFromNow = Utils.oneYearFromNow;
                $scope.dateSelected = false;
                $scope.date = $rootScope.$storage.groups.tripDate.date || null;
                $scope.onDatePickerChange();
            };

            //Listen for date picker change
            $scope.onDatePickerChange = function () {
                // Handle filter on date change
                $scope.$on('datepickerChange', function (event, date) {
                    if (!date || !Constants.groups.ticketTypeCategory) return;
                    $rootScope.$storage.groups.tripDate.date = angular.copy(date);
                    $scope.dateSelected = true;
                    Presentations.get($rootScope.$storage.groups.tripDate.date, Constants.groups.ticketTypeCategory, function (response) {
                        Groups.getPresentationsBatch($rootScope.$storage.selectedTicketType.id, false, function (response) {
                            var planYourDay = angular.copy(response);
                            delete planYourDay.hallsOfFocus;
                            $rootScope.$storage.groups.planYourDay = angular.extend($rootScope.$storage.groups.planYourDay, planYourDay);
                            $rootScope.$storage.groups.hallOfFocus.halls = response.hallsOfFocus;
                        });

                        Groups.getExhibitionPresentations($rootScope.$storage.groups.tripDate.date, false, function (presentations) {
                            //Set storage presentations to response
                            $rootScope.$storage.selectedTicketType.presentations = presentations;
                        });
                    });
                });
            };

            // Validate adult child ratio
            $scope.isValidAdultRatio = function () {
                return !!Groups.isValidAdultRatio();
            };

            //Validate form in a valid state
            $scope.isValid = function () {
                var valid = true;
                if (!$scope.isValidAdultRatio()) valid = false;
                return valid;
            };

            //Go
            $scope.init();

        }
    }

}]).directive('groupsTransportation', [function () {
    return {
        restrict: 'E',
        templateUrl: 'Content/views/groups/groups-transportation.html',
        controller: function($scope, $rootScope, $log, Utils, Presentations, Tickets, Cart, Groups, Constants) {

            //Initialize
            $scope.init = function () {
                $scope.busGroupType = Constants.groups.busGroupType;
                $scope.maxBuses = Constants.groups.maxBuses;
                $scope.busOptionValues = null;
                //If transportation API hasnt been called yet, call it
                if (!$rootScope.$storage.groups.transportation.priceTypes) $scope.getTransportation();
                else {
                    //Grab busOptionValues again
                    if ($rootScope.$storage.groups.transportation.remainingSeats) $scope.busOptionValues = $scope.getBusOptionValues($rootScope.$storage.groups.transportation.remainingSeats);
                    else $log.error('$rootScope.$storage.groups.transportation.remainingSeats expected to have value');
                }
            };

            //Get transportation from Rest API
            $scope.getTransportation = function () {
                
                Groups.getTransportation($rootScope.$storage.groups.tripDate.date, function (transportation) {

                    if (!transportation.remainingSeats || !transportation.performanceId || !angular.isObject(transportation.priceTypes)) {
                        return $log.error('Invalid response from Groups.getTransportation in groupsTransportation directive');
                    }
                    $rootScope.$storage.groups.transportation = angular.extend($rootScope.$storage.groups.transportation, transportation);
                    if (!$rootScope.$storage.groups.transportation.formData.priceTypeGroup) $scope.setSelectedPriceTypeGroup();
                    $scope.busOptionValues = $scope.getBusOptionValues($rootScope.$storage.groups.transportation.remainingSeats);
                });
            };
            
            $scope.getBusOptionValues = function (maxBuses) {
                return Groups.getBusOptionValues(maxBuses);
            };

            $scope.restoreDefaults = function () {
                $rootScope.$storage.groups.transportation.formData.priceTypeQty = Storage.defaults.session.groups.transportation.formData.priceTypeQty;
            };

            //Get and set the selectedPriceTypeGroup
            $scope.setSelectedPriceTypeGroup = function () {

                var selectedPriceTypeGroup = null;
                //If undefined return
                if (!$rootScope.$storage.groups.transportation.formData.priceTypeId && typeof $rootScope.$storage.groups.transportation.formData.priceTypeId !== 'number') return;
                angular.forEach($rootScope.$storage.groups.transportation.priceTypes, function (val) {
                    if (val.id === $rootScope.$storage.groups.transportation.formData.priceTypeId) {
                        selectedPriceTypeGroup = val.priceTypeGroup;
                    }
                });

                if (selectedPriceTypeGroup) $rootScope.$storage.groups.transportation.formData.priceTypeGroup = selectedPriceTypeGroup;
                else $log.error('selectedPriceTypeGroup not found in setSelectedPriceTypeGroup');
            };

            //Go
            $scope.init();

            return;

        }
    }
}]).directive('groupsHallOfFocus', [function () {
    return {
        restrict: 'E',
        templateUrl: 'Content/views/groups/groups-hall-of-focus.html',
        controller: function ($scope, $rootScope, $log, filterFilter, Utils, Presentations, Tickets, Cart, Groups, Itinerary) {
            
            //Initialize
            $scope.init = function () {
                $scope.hasPresentations = false;
                if (!$rootScope.$storage.groups.hallOfFocus.halls.length) $scope.getHallsOfFocus(function (halls) { $scope.configureHalls(halls); });
                else $scope.configureHalls($rootScope.$storage.groups.hallOfFocus.halls)
            };

            //Get halls from Rest API
            $scope.getHallsOfFocus = function (callback) {
                Groups.getHallsOfFocus($rootScope.$storage.groups.tripDate.date, callback);
            };

            $scope.configureHalls = function (halls) {
                //Loops through each hall and apply
                angular.forEach(halls, function (hall) {
                    if (hall.useZonesForTimes) $rootScope.$storage.groups.hallOfFocus.hasPresentations = true;
                    if (filterFilter($rootScope.$storage.itinerary, { id: hall.defaultPresentationId, zoneId: hall.zoneId }).length) hall.selected = true;
                });
                //Set halls to scope
                $rootScope.$storage.groups.hallOfFocus.halls = halls;
                //Apply Presentations property to halls
                $scope.applyPresentations();
            };

            // Expose Itinierary to scope
            $scope.itinerary = Itinerary;

            // Hall selected
            $scope.isHallSelected = function () {
                var result = false;
                angular.forEach($rootScope.$storage.groups.hallOfFocus.halls, function (hall) {
                    if (filterFilter($rootScope.$storage.itinerary, { id: hall.defaultPresentationId }).length) result = true;
                });
                return result;
            };

            // Zone selected
            $scope.isZoneSelected = function () {
                var result = false;
                angular.forEach(filterFilter($rootScope.$storage.groups.hallOfFocus.halls, { useZonesForTimes: true }), function (hall) {
                    if (filterFilter($rootScope.$storage.itinerary, { id: hall.defaultPresentationId }).length) result = true;
                });
                return result;
            };

            //Apply Presentations property to halls
            $scope.applyPresentations = function () {
                angular.forEach($rootScope.$storage.groups.hallOfFocus.halls, function (hall) {
                    hall.presentations = [];
                    if (hall.useZonesForTimes) {
                        Groups.getPresentations(hall.defaultPresentationId, function (presentations) {
                            hall.presentations = presentations;
                            if (!$scope.hasPresentations) {
                                $scope.hasPresentations = true;
                            }
                        });
                    }
                });
            };

            $scope.updateSelectedHall = function (hall) {
                if (hall.selected) $rootScope.$storage.groups.hallOfFocus.formData.selectedHalls.push(hall.defaultPresentationId);
                else $rootScope.$storage.groups.hallOfFocus.formData.selectedHalls.splice($rootScope.$storage.groups.hallOfFocus.formData.selectedHalls.indexOf(hall), 1);
            };
            
            //Go
            $scope.init();

        }
    }
}]).directive('groupsPlanYourDay', [function () {
    return {
        restrict: 'E',
        templateUrl: 'Content/views/groups/groups-plan-your-day.html',
        controller: function ($scope, $rootScope, $timeout, filterFilter, Utils, Presentations, Exhibitions, Tickets, Cart, Groups, Constants) {
            $scope.lunchroomRequired = false;
            $scope.seeExhibitions = false;
            $rootScope.$storage.groups.planYourDay.grades = ($rootScope.$storage.groups.planYourDay.grades.length) ? $rootScope.$storage.groups.planYourDay.grades : Constants.grades;
            
            $scope.init = function () {

                // Expose Itinierary to scope
                $scope.itinerary = Itinerary;
                $scope.exhibitions = [];

                //Get arrival times
                //If the call was not made or interuppted by a page refresh make with a spinner when arriving at this step
                if (!$rootScope.$storage.groups.planYourDay.arrivalTimePresentations.length || !$rootScope.$storage.groups.planYourDay.lunchrooms.length) {
                    Groups.getPresentationsBatch($rootScope.$storage.selectedTicketType.id, true, function (response) {
                        $rootScope.$storage.groups.planYourDay = angular.extend($rootScope.$storage.groups.planYourDay, response);
                        wrapBatchPresentations();
                    });
                } else {
                    wrapBatchPresentations();
                    $scope.isValid();
                }

            };

            //Get Exhibitions
            $scope.getExhibitions = function () {
                $rootScope.$storage.spinner = true;
                Exhibitions.query(function (exhibitions) {
                    $rootScope.$storage.spinner = false;
                    // Add exhibitions to scope
                    $scope.exhibitions = exhibitions;
                    //Add for Exhibitions
                    angular.forEach($rootScope.$storage.selectedTicketType.presentations, function (presentation) {
                        presentation.forExhibitions = true;
                    });
                    // Process presentations
                    Presentations.attach($rootScope.$storage.selectedTicketType.presentations, exhibitions);
                    // Force digest via timeout
                    $timeout(function () {
                        $rootScope.$broadcast('presentationsReady');
                    });
                }, function () {
                    $rootScope.$storage.spinner = false;
                });
            };

            $scope.updateSelectedGrades = function () {
                var selectedGrades = filterFilter($rootScope.$storage.groups.planYourDay.grades, { selected: true });
                $rootScope.$storage.groups.planYourDay.formData.selectedGrades = selectedGrades.map(function (grade) {
                    return grade.value;
                });
            };

            $scope.isValid = function () {
                var selectedGrades = false;
                var arrivalTime = false;

                if ($rootScope.$storage.groups.planYourDay.formData.selectedGrades.length) selectedGrades = true;
                
                angular.forEach($rootScope.$storage.groups.planYourDay.arrivalTimePresentations.presentations, function (presentation) {
                    if (Itinerary.hasPresentation(presentation)) arrivalTime = true;
                });

                return (selectedGrades && arrivalTime);
            };

            //Wrap batch presentations with exhibition objects
            var wrapBatchPresentations = function () {
                $rootScope.$storage.groups.planYourDay.arrivalTimePresentations = Groups.wrapWithExhibition($rootScope.$storage.groups.planYourDay.arrivalTimePresentations);
                $rootScope.$storage.groups.planYourDay.lunchrooms = Groups.wrapWithExhibition($rootScope.$storage.groups.planYourDay.lunchrooms);
            };

            $scope.init();

        }
    }
}]).directive('groupsReview', [function () {
    return {
        restrict: 'E',
        templateUrl: 'Content/views/groups/groups-review.html',
        controller: function ($scope, $rootScope, Utils, Presentations, Tickets, Cart, Groups) {

        }
    }
}])

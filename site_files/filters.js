/*************************** Filters ***************************/

/** 
  * By Key
  * Provide array of objects, key name and key value. This will return the object that match key name and key value.
  */
App.filter('byKey', function () {
    return function (arrayObjs, keyName, keyValue) {
        return $(arrayObjs).filter(function () {
            return this[keyName] === keyValue;
        }).first()[0];
    };
});

/** 
  * Get Index By Key
  * Provide array of objects, key name and key value. This will return the object index that match key name and key value.
  */
App.filter('getIndexByKey', function () {
    return function (arrayObjs, keyName, keyValue) {
        return $.map(arrayObjs, function (obj, index) {
            if (obj[keyName] === keyValue) {
                return index;
            }
        })
    };
});

/** 
  * isPresentationAvailable
  */
App.filter('isPresentationAvailable', function () {
    return function (presentations, tickets) {
        var availablePresentations = [];
        angular.forEach(presentations, function (value, key) {
            if (value.unlimitedSeats) {
                availablePresentations.push(value);
            } else {
                if (value.remainingSeats >= tickets) availablePresentations.push(value);
            }
        })
        return availablePresentations;
    };
});

/** 
  * Required Label
  * Adds a red star (*) next to label name
  */
App.filter('requiredLabel', function () {
    return function (label) {
        return label + ' <span class="text-danger">*</span>';
    };
});

/**
 * Truncate Filter
 * @Param text
 * @Param length, default is 10
 * @Param end, default is "..."
 * @return string
 */
App.filter('truncate', function () {
    return function (text, length, end) {
        if (isNaN(length))
            length = 10;
        if (end === undefined)
            end = "...";
        if (text.length <= length || text.length - end.length <= length)
            return text;
        else
            return String(text).substring(0, length - end.length) + end;
    };
});

/** 
  * Filter past presentations
  */
App.filter('pastPresentations', function () {
    return function (presentations) {
        if (!presentations) return;
        var now = new Date(),
            filteredPresentations = presentations.filter(function (presentation) {
                var startTime = new Date(presentation.startTime);
                return startTime.getTime() > now.getTime();
            });
        return filteredPresentations;
    };
});

/** 
  * Filter unique
  */
App.filter('unique', function () {
    return function (collection, keyname) {
        var output = [],
            keys = [];
        angular.forEach(collection, function (item) {
            var key = item[keyname];
            if (keys.indexOf(key) === -1) {
                keys.push(key);
                output.push(item);
            }
        });
        return output;
    };
});

/** 
  * Filter groupBy
  */
App.filter('groupBy', ['$parse', function ($parse) {
    return function (list, group_by) {

        var filtered = [];
        var prev_item = null;
        var group_changed = false;
        // this is a new field which is added to each item where we append "_CHANGED"
        // to indicate a field change in the list
        //was var new_field = group_by + '_CHANGED'; - JB 12/17/2013
        var new_field = 'group_by_CHANGED';

        // loop through each item in the list
        angular.forEach(list, function (item) {

            group_changed = false;

            // if not the first item
            if (prev_item !== null) {

                // check if any of the group by field changed

                //force group_by into Array
                group_by = angular.isArray(group_by) ? group_by : [group_by];

                //check each group by parameter
                for (var i = 0, len = group_by.length; i < len; i++) {
                    if ($parse(group_by[i])(prev_item) !== $parse(group_by[i])(item)) {
                        group_changed = true;
                    }
                }

            } // otherwise we have the first item in the list which is new
            else {
                group_changed = true;
            }

            // if the group changed, then add a new field to the item
            // to indicate this
            if (group_changed) {
                item[new_field] = true;
            } else {
                item[new_field] = false;
            }

            filtered.push(item);
            prev_item = item;

        });

        return filtered;
    };
}]);

/** 
  * Filter enumToTitle
  */
App.filter('enumToTitle', function () {
    return function (text) {
        if (!angular.isString(text)) return;
        else return text.replace(/([A-Z]+)/g, " $1").replace(/([A-Z][a-z])/g, " $1");
    };
});

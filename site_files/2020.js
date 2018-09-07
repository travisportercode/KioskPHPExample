
/*************************** 2020 Controller ***************************/

App.controller('twentyTwentyCtrl', ['$scope', '$rootScope', function ($scope, $rootScope) {

    $scope.options = {
        prefix: '.sp',
        padding: 'padding',
        margin: 'margin',
        inner: 'in',
        outer: 'out',
        percent: 'pr',
        pixel: 'px',
        tp: 'top',
        rt: 'right',
        bm: 'bottom',
        lt: 'left',
        sep: '-',
        block1: '',
        block2: '',
        block3: '',
        block4: '',
        block5: '',
        px: 'px',
        pr: '%',
        sc: ',',
        curll: '{',
        curlr: '}',
        sp: ' ',
        br: '<br />',
        important: '!important'
    }

    $scope.twentyTwenty = '';

    /** GLOBAL **/
    // Padding in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.percent + ' { padding: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Padding in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.pixel + ' { padding: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.percent + ' { margin: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.pixel + ' { margin: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;

    /** TOP **/
    // Padding in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.tp + ' { padding-top: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Padding in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.tp + ' { padding-top: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.tp + ' { margin-top: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.tp + ' { margin-top: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;

    /** RIGHT **/
    // Padding in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.rt + ' { padding-right: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Padding in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.rt + ' { padding-right: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.rt + ' { margin-right: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.rt + ' { margin-right: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;

    /** BOTTOM **/
    // Padding in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.bm + ' { padding-bottom: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Padding in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.bm + ' { padding-bottom: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.bm + ' { margin-bottom: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.bm + ' { margin-bottom: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;

    /** LEFT **/
    // Padding in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.lt + ' { padding-left: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Padding in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.inner + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.lt + ' { padding-left: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in percent
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.percent + $scope.options.sep + $scope.options.lt + ' { margin-left: ' + i + '%' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;
    // Margin in pixels
    for (var i = 0; i < 101; i++) $scope.twentyTwenty += $scope.options.prefix + $scope.options.sep + $scope.options.outer + $scope.options.sep + i + $scope.options.pixel + $scope.options.sep + $scope.options.lt + ' { margin-left: ' + i + 'px' + $scope.options.sp + $scope.options.important + '; }' + $scope.options.br;

}]);


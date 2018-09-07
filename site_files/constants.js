'use strict';

App.constant('Constants', {
    devices: ['Desktop', 'Tablet', 'Mobile', 'Kiosk'],
    admin: {
        password: '555'
    },
    cookieDomain: (document.domain === 'localhost') ? '' : 'amnh.org',
    cookiePath: '/',
    cookieKeys: {
        authorization: 'AuthToken'
    },
    groups: {
        adultChildRatio: 0.1,
        maxBuses: 10,
        ticketTypeCategory: 'GeneralAdmission',
        busGroupType: 'Bus',
        schoolGroupType: 'School',
        priceTypeGroups: {
            adults: ['NycGroupAdult', 'NycGroupSenior', 'OutOfTownGroupAdult', 'OutOfTownGroupSenior'],
            children: ['NycGroupChild', 'NycGroupStudent', 'OutOfTownGroupChild', 'OutOfTownGroupStudent']
        }
    },
    grades: [
        {label: 'Pre-K', value: 'PK'},
        {label: 'K', value: '0K'},
        {label: '1', value: '01'},
        {label: '2', value: '02'},
        {label: '3', value: '03'},
        {label: '4', value: '04'},
        {label: '5', value: '05'},
        {label: '6', value: '06'},
        {label: '7', value: '07'},
        {label: '8', value: '08'},
        {label: '9', value: '09'},
        {label: '10', value: '10'},
        {label: '11', value: '11'},
        {label: '12', value: '12'},
        {label: 'College', value: '0C'}
    ],
    months: [{
        code: '1',
        name: 'January'
    }, {
        code: '2',
        name: 'February'
    }, {
        code: '3',
        name: 'March'
    }, {
        code: '4',
        name: 'April'
    }, {
        code: '5',
        name: 'May'
    }, {
        code: '6',
        name: 'June'
    }, {
        code: '7',
        name: 'July'
    }, {
        code: '8',
        name: 'August'
    }, {
        code: '9',
        name: 'September'
    }, {
        code: '10',
        name: 'October'
    }, {
        code: '11',
        name: 'November'
    }, {
        code: '12',
        name: 'December'
    }],
    printURL: 'http://localhost/printTkts/printTkts.php',
    personalization: {
        env: {
            dev: {
                rootUrl: 'http://dt-dev-004.amnh.org'
            },
            prod: {
                rootUrl: 'http://www.amnh.org'
            }
        },
        iframePath: '/layout/set/iframe/',
        logout: '/apiuser/logout'
    },
    env: {
        local: {
            signature: 'local', /* Signature to identify when on localhost - http://localhost:58260 */
            label: 'local'
        },
        dev: {
            signature: 'devapi', /* Signature to identify when on dev - http://dt-devapi-001.internal.amnh.org */
            label: 'dev'
        },
        test: {
            signature: 'tktg', /* Signature to identify when on test - https://dt-tktg-101.internal.amnh.org */
            label: 'test'
        },
        prod: {
            label: 'prod'
        }
    },
    test: {
        payment: {
            email: 'new.user+' + new Date().getTime() + '@door3.com',
            cardholderName: 'New User',
            firstName: 'John',
            lastName: 'Smith',
            cardNo: '4111111111111111',
            cardExpiryMonth: '3',
            cardExpiryYear: '2015',
            cardSecurityCode: '150',
            postalCode: '10007'
        },
        user: {
            address: '22 Cortlandt Street 1101',
            country: {
                id: 1,
                name: 'USA'
            },
            state: {
                stateCode: 'NY',
                name: 'New York'
            },
            city: 'NY'
        }
    }
});
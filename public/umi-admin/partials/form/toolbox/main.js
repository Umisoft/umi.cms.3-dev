define(
    ['App', './formService', './formHelper'],
    function(UMI, formService, formHelper) {
        'use strict';

        return function() {
            formService();
            formHelper();
        };
    }
);
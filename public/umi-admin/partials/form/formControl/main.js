define(
    ['App', './form.helper', './form.service', './form.controller', './form.view'],

    function(UMI, formHelper, formService, formController, formView) {
        'use strict';

        return function() {
            formHelper();
            formService();
            formController();
            formView();
        };
    }
);

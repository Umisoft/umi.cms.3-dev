define(
    ['App', './toolbox/main', './formControllerMixin', './formViewMixin', './formElementsMixin', './formElementMixin',
        './formSimple/main', './formCollection/main', './element/main'],

    function(UMI, toolbox, formControllerMixin, formViewMixin, formElementsMixin, formElementMixin, formSimple,
        formCollection, element) {
        'use strict';

        toolbox();
        formControllerMixin();
        formViewMixin();
        formElementsMixin();
        formElementMixin();
        formSimple();
        formCollection();
        element();
    }
);
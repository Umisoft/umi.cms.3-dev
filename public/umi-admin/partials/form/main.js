define(
    ['App', './toolbox/main', './formControllerMixin', './formViewMixin', './formElementsFactory',
        './formElementsMixin', './formElementMixin', './formSimple/main', './formCollection/main', './element/main'
    ],
    function(UMI, toolbox, formControllerMixin, formViewMixin, formElementsFactory, formElementsMixin, formElementMixin,
        formSimple, formCollection, element) {
        'use strict';

        toolbox();
        formControllerMixin();
        formViewMixin();
        formElementsFactory();
        formElementsMixin();
        formElementMixin();
        formSimple();
        formCollection();
        element();
    }
);
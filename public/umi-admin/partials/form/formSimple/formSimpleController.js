define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSimpleController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                formElementsBinding: 'control.meta.elements'
            });
        };
    }
);
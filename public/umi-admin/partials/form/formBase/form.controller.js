define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormBaseController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                formElementsBinding: 'control.meta.elements'
            });
        };
    }
);
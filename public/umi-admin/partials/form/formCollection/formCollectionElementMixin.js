define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormCollectionElementMixin = Ember.Mixin.create(UMI.FormElementMixin);
        };
    }
);
define(['App'], function(UMI) {
    'use strict';

    return function() {

        UMI.PopupController = Ember.Controller.extend({
            templateParams: null,
            /**
             * @abstract
             * @property
             */
            popupType: null
        });
    };
});
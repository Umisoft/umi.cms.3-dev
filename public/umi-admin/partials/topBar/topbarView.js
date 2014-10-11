define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.TopbarView = Ember.View.extend({
                templateName: 'partials/topBar'
            });
        };
    }
);
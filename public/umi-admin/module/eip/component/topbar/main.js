define(['Ember'], function(Ember) {
    'use strict';

    return function(namespaceApp) {
        namespaceApp.TopbarView = Ember.View.extend({
            template: Ember.Handlebars.compile('TEST')
        });
    };
});
define(['Ember'], function(Ember) {
    'use strict';

    return function(namespaceApp) {
        namespaceApp.TopbarView = Ember.View.extend({
            classNames: ['eip-topbar'],
            templateName: 'component/topbar/main'
        });
    };
});
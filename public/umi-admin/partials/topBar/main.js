define(['./controlPanel/main', './modulesList/main', 'App'], function(controlPanel, modulesList, UMI) {
    'use strict';

    UMI.TopBarView = Ember.View.extend({
        templateName: 'partials/topBar',

        activeProject: function() {
            return window.location.host;
        }.property(),

        siteUrl: function() {
            return Ember.get(window, 'UmiSettings.baseSiteURL');
        }.property(),

        userName: function() {
            return this.get('controller.user._data.displayName');
        }.property('controller.user.displayName'),

        userId: function() {
            return this.get('controller.user.id');
        }.property('controller.user.id'),

        modules: function() {
            return this.get('controller.modules');
        }.property()
    });

    controlPanel();
    modulesList();
});

define([
    'App'
], function(UMI) {
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

        modules: function() {
            return this.get('controller.modules');
        }.property()
    });
});

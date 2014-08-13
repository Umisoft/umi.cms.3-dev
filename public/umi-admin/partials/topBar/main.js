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
            return Ember.get(window, 'UmiSettings.user.displayName');
        }.property(),//TODO: reload after logout

        modules: function() {
            return this.get('controller.modules');
        }.property()
    });
});
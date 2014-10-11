define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.TopbarController = Ember.ObjectController.extend({
                needs: ['application'],

                applicationController: Ember.computed.alias('controllers.application'),

                routeIsTransitionBinding: 'applicationController.routeIsTransition',

                modulesBinding:'applicationController.modules',

                activeProject: function() {
                    return window.location.host;
                }.property(),

                siteUrl: function() {
                    return Ember.get(window, 'UmiSettings.baseSiteURL');
                }.property(),

                userName: function() {
                    return Ember.get(window, 'UmiSettings.user.displayName');
                }.property(),//TODO: reload after logout

                localization: Ember.Object.extend({
                    list: [
                        {displayName: 'Русский', id: 'ru-Ru', isActive: true},
                        {displayName: 'English', id: 'en-En'},
                        {displayName: 'հայերեն', id: 'ar-Ar'}
                    ],

                    activeLocale: function() {
                        return this.get('list').findBy('isActive', true);
                    }.property('list.[].isActive')
                }).create()
            });
        };
    }
);
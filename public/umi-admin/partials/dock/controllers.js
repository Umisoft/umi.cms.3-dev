define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],
            modulesBinding: 'controllers.application.modules',
            activeModuleBinding: 'controllers.module.model'
        });
    };
});
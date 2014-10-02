define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],
            modulesBinding: 'controllers.application.modules',
            sortedModules: function() {
                var userSettings = UMI.Utils.LS.get('dock');
                var modules = this.get('modules');
                if (Ember.typeOf(userSettings) === 'array') {
                    var sortedModules = [];
                    modules = modules.slice();
                    for (var i = 0, l = userSettings.length; i < l; i++) {
                        for (var j = 0, l2 = modules.length; j < l2; j++) {
                            if (modules[j] && modules[j].name === userSettings[i]) {
                                sortedModules.push(modules[j]);
                                modules.splice(j, 1);
                            }
                        }
                    }
                    return sortedModules.concat(modules);
                } else {
                    return modules;
                }
            }.property('modules'),
            activeModuleBinding: 'controllers.module.model'
        });
    };
});
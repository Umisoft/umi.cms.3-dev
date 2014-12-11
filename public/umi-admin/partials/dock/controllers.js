define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],

            modulesBinding: 'controllers.application.modules',

            sortedModules: function() {
                var userSettings = UMI.Utils.LS.get('dock.sortedOrder');
                var modules = this.get('modules');
                if (Ember.typeOf(userSettings) === 'array') {
                    var sortedModules = [];
                    modules = modules.slice();
                    for (var i = 0, l = userSettings.length; i < l; i++) {
                        for (var j = 0, l2 = modules.length; j < l2; j++) {
                            if (modules[j].name === userSettings[i]) {
                                sortedModules.push(modules[j]);
                                modules.splice(j, 1);
                                l2--;
                            }
                        }
                    }
                    return sortedModules.concat(modules);
                } else {
                    return modules;
                }
            }.property('modules').volatile(),

            activeModuleBinding: 'controllers.module.model',

            modes: [
                {name: 'small', title: 'Little', isActive: false},
                {name: 'big', title: 'Big', isActive: false},
                {name: 'dynamic', title: 'Dynamic', isActive: false},
                {name: 'list', title: 'Listed', isActive: false}
            ],

            activeMode: function() {
                return this.get('modes').findBy('isActive', true);
            }.property('modes.@each.isActive'),

            init: function() {
                var activeMode = UMI.Utils.LS.get('dock.activeModeName');
                var modes = this.get('modes');
                if (!activeMode || !modes.findBy('name', activeMode)) {
                    activeMode = Ember.get(modes[0], 'name');
                }
                Ember.set(modes.findBy('name', activeMode), 'isActive', true);
            }
        });
    };
});

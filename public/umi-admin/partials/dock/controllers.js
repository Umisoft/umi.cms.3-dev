define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.DockController = Ember.ObjectController.extend({
            needs: ['application', 'module'],
            modulesBinding: 'controllers.application.modules',

            _modules: [],

            sortedModules: function() {
                return this.get('_modules').sortBy('index');
            }.property('_modules.@each.index'),

            resortModules: function(newOrder) {
                var modules = this.get('_modules');
                this.propertyWillChange('_modules');
                for (var i = 0, l = modules.length; i < l; i++) {
                    var ind = newOrder.indexOf(modules[i].name);
                    if (ind >= 0) {
                        Ember.set(modules[i], 'index', ind);
                    }
                }
                this.set('_modules', modules);
                this.propertyDidChange('_modules');
            },

            hideModule: function(module) {
                var modules = this.get('_modules');
                this.propertyWillChange('_modules');
                for (var i = 0, l = modules.length; i < l; i++) {
                    if (modules[i].name === module) {
                        Ember.set(modules[i], 'isHidden', true);
                    }
                }
                this.set('_modules', modules);
                this.propertyDidChange('_modules');
                $(window).trigger('dockChange');
            },

            showModule: function(module) {
                var modules = this.get('_modules');
                this.propertyWillChange('_modules');
                for (var i = 0, l = modules.length; i < l; i++) {
                    if (modules[i].name === module) {
                        Ember.set(modules[i], 'isHidden', false);
                    }
                }
                this.set('_modules', modules);
                this.propertyDidChange('_modules');
                $(window).trigger('dockChange');
            },

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
                this._super();
                var sortSettings = UMI.Utils.LS.get('dock.sortedOrder');
                var hideSettings = UMI.Utils.LS.get('dock.hiddenModules');
                var modules = this.get('modules');
                this.propertyWillChange('_modules');
                if (Ember.typeOf(hideSettings) === 'array') {
                    for (var i = 0, l = modules.length; i < l; i++) {
                        var ind = hideSettings.indexOf(modules[i].name);
                        Ember.set(modules[i], 'isHidden', (ind >= 0));
                    }
                }
                this.set('_modules', modules);
                this.propertyDidChange('_modules');
                if (Ember.typeOf(sortSettings) === 'array') {
                    this.resortModules(sortSettings);
                }


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

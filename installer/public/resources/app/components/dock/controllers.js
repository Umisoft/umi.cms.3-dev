define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DockController = Ember.ArrayController.extend({
            content: function(){
                var data = this.get('modules');
                if(!data){
                    return;
                }
                var modules = data.modules;
                var resources = data.resources;
                var i;
                var results = [];
                for(var j = 0; j < modules.length; j++){
                    var components = [];
                    for(i = 0; i < modules[j].components.length; i++){
                        components.push(Ember.Object.create(modules[j].components[i]));
                    }
                    modules[j].components = components;
                    results.push(Ember.Object.create(modules[j]));
                }
                var module;
                var component;
                for(i = 0; i < resources.length; i++){
                    module = results.findBy('name', resources[i].module);
                    component = module.components.findBy('name', resources[i].component);
                    if(component){
                        component.collection = resources[i].collection;
                    }
                }
                return results;
            }.property('modules'),
            sortAscending: true,
            sortProperties: ['index'],
            modules: null
        });
    };
});
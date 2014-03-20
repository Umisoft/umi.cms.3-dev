define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DockController = Ember.ArrayController.extend({
            content: function(){
                var modules = this.get('modules');
                if(!modules){
                    return;
                }
                var i;
                var results = [];
                for(var j = 0; j < modules.length; j++){
                    var components = [];
                    if(modules[j].hasOwnProperty('components')){
                        for(i = 0; i < modules[j].components.length; i++){
                            components.push(Ember.Object.create(modules[j].components[i]));
                        }
                    }
                    modules[j].components = components;
                    results.push(Ember.Object.create(modules[j]));
                }
                return results;
            }.property('modules'),
            sortAscending: true,
            sortProperties: ['index'],
            modules: null
        });
    };
});
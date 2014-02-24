define(['./app'], function(UMI){
    'use strict';

    return function(){

        UMI.ComponentController = Ember.ObjectController.extend({
            treeSettings: false,
            treeType: null,
            hasTree: false
        });

        UMI.ComponentModeController = Ember.ArrayController.extend({
            content: function(){
                var componentMode = this.get('modes');
                if( componentMode ){
                    var buttons = [];
                    componentMode = componentMode.get('modes')[this.get('id')];
                    for(var i = 0; i < componentMode.titles.length; i++){
                        buttons.push(Ember.Object.create({slug: componentMode.slug[i], title: componentMode.titles[i], contentType: componentMode.contentType[i], current: (componentMode.current === i ? true : false), resources: componentMode.resources[i]}));
                    }
                    return buttons;
                }
            }.property('modes', 'id'),
            modes: null,
            id: null
        });

        UMI.ContentController = Ember.ObjectController.extend({
            activeNode: '',
            queryParams: ['objectId'],
            objectId: null
        });
    };
});
define([], function(){
    'use strict';
    return function(UMI){
        UMI.ComponentController = Ember.ObjectController.extend({
            treeType: null,
            hasTree: null,
            controls: null,
            context: null,
            selectedContext: 'root',
            contentControls: function(){
                var allControls = this.get('controls');
                var context = this.get('context');
                var selectedContext  = this.get('selectedContext') === 'root' ? 'emptyContext' : 'selectedContext';
                var controls = context[selectedContext].content.controls;
                var contentControls = [];
                for(var i = 0; i < allControls.length; i++){
                    if(controls.indexOf(allControls[i].name) !== -1){
                        contentControls.push(Ember.Object.create(allControls[i]));
                    }
                }
                return contentControls;
            }.property('context', 'selectedContext')
        });
    };
});
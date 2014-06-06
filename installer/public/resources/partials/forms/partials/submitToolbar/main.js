define(['App', 'text!./submitToolbar.hbs'], function(UMI, submitToolbarTpl){
    "use strict";

    return function(){
        UMI.SubmitToolbarView = Ember.View.extend({
            layout: Ember.Handlebars.compile(submitToolbarTpl),
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: UMI.ToolBarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.splitButtonBehaviour.dropUp);
                    var behaviour = this.get('context.behaviour.name');
                    if(behaviour){
                        behaviour = UMI.dropdownButtonBehaviour.get(behaviour) || {};
                    } else{
                        behaviour = {};
                    }
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});

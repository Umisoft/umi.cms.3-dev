define(['App', 'text!./submitToolbar.hbs', 'toolbar'], function(UMI, submitToolbarTpl){
    "use strict";

    return function(){
        UMI.SubmitToolbarView = Ember.View.extend({
            layout: Ember.Handlebars.compile(submitToolbarTpl),
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: UMI.ToolbarElementView.extend({
                splitButtonView: function(){
                    var instance = UMI.SplitButtonView.extend(UMI.splitButtonBehaviour.dropUp);
                    var behaviour = this.get('context.behaviour.name');
                    if(behaviour){
                        behaviour = UMI.splitButtonBehaviour.get(behaviour) || {};
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

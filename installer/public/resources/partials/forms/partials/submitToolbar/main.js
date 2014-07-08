define(['App', 'toolbar'], function(UMI){
    "use strict";

    return function(){
        UMI.SubmitToolbarView = Ember.View.extend({
            layoutName: 'partials/form/submitToolbar',
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

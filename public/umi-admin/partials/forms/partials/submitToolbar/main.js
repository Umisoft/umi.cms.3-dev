define(['App', 'toolbar'], function(UMI) {
    "use strict";

    return function() {
        UMI.SubmitToolbarView = Ember.View.extend({
            layoutName: 'partials/form/submitToolbar',
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function() {
                    var instance = UMI.SplitButtonView.extend(UMI.splitButtonBehaviour.dropUp);
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});

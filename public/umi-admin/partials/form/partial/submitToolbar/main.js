define(['App', 'toolbar'], function(UMI) {
    "use strict";

    return function() {
        UMI.SubmitToolbarView = Ember.View.extend({
            layoutName: 'partials/form/submitToolbar',
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons'],
            elementView: Ember.View.extend(UMI.ToolbarElement, {
                splitButtonView: function() {
                    var instance = UMI.SplitButtonView.extend({});
                    var behaviourName = this.get('context.behaviour.name');
                    var behaviour = Ember.get(UMI.splitButtonBehaviour, behaviourName) || {};
                    behaviour.extendButton = behaviour.extendButton || {};
                    behaviour.extendButton.dataOptions = function() {
                        return 'side: top; align: right;';
                    }.property();
                    behaviour.extendButton.classNames = ['f-arrowed-top'];
                    behaviour.listClassNames = 'f-dropdown-padding';
                    instance = instance.extend(behaviour);
                    return instance;
                }.property()
            })
        });
    };
});

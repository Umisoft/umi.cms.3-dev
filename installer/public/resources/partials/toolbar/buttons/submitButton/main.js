define(['App'],
    function(UMI){
        "use strict";

        return function(){
            UMI.SubmitButtonView = Ember.View.extend({
                tagName: 'button',
                template: Ember.Handlebars.compile('{{view.button.displayName}}'),
                classNames: ['s-margin-clear', 'umi-button', 'wide'],
                attributeBindings: ['type'],
                type: 'submit'
            });
        };
    }
);
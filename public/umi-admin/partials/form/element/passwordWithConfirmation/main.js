define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormPasswordWithConfirmationElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "passwordWithConfirmationElement" object=view.object meta=view.meta}}')
        });

        UMI.PasswordWithConfirmationElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],
            type: 'text'
        });
    };
});
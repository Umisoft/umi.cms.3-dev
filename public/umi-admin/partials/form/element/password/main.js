define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormPasswordElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "passwordElement" object=view.object meta=view.meta}}')
        });

        UMI.PasswordElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],
            type: 'text'
        });
    };
});
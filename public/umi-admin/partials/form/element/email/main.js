define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormEmailElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "emailElement" object=view.object meta=view.meta}}')
        });

        UMI.EmailElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-email'],
            type: 'email'
        });
    };
});
define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormColorElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "colorElement" object=view.object meta=view.meta}}')
        });

        UMI.ColorElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-color'],
            type: 'color'
        });
    };
});
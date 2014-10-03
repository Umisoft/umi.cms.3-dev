define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormColorElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "colorElement" object=view.object meta=view.meta}}')
        });

        UMI.ColorElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-color'],
            type: 'color'
        });
    };
});
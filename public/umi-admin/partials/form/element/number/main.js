define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormNumberElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "numberElement" object=view.object meta=view.meta}}')
        });

        UMI.NumberElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-number'],
            type: 'number'
        });
    };
});
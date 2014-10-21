define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormHiddenElementMixin = Ember.Mixin.create({
            classNames: ['hide'],
            template: Ember.Handlebars.compile('{{view "hiddenElement" object=view.object meta=view.meta}}')
        });

        UMI.HiddenElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-hidden'],
            type: 'hidden'
        });
    };
});
define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormImageElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "imageElement" object=view.object meta=view.meta}}')
        });

        UMI.ImageElementView = UMI.FileElementView.extend({});
    };
});
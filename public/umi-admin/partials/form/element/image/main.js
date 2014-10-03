define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormImageElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "imageElement" object=view.object meta=view.meta}}')
        });

        UMI.ImageElementView = UMI.FileElementView.extend({});
    };
});
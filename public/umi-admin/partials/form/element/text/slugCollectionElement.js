define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormSlugCollectionElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "slugCollectionElement" object=view.object meta=view.meta}}')
        });

        UMI.SlugCollectionElementView = UMI.TextElementView.extend({
            isFillByUser: false,

            keyUp: function() {
                var isFillByUser = !!this.get('object.' + this.get('meta.dataSource'));
                this.set('isFillByUser', isFillByUser);
            },

            slugGenerate: function() {
                var object = this.get('object');
                var displayName = object.get('displayName');

                if (!this.get('isFillByUser')) {
                    object.set('slug', UMI.FormHelper.getSlug(displayName));
                }
            }.observes('object.displayName')
        });
    };
});
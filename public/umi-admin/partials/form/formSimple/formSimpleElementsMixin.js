define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormSimpleElementsMixin = Ember.Mixin.create(UMI.FormElementsMixin, {
                /**
                 * Фабрика элементов
                 * @property elementFactory
                 */
                elementFactory: function() {
                    return UMI.FormElementFactory.create({
                        container: this.container,

                        singleCollectionObjectRelation: 'FormSingleCollectionObjectRelationElement'
                    });
                }.property()
            });
        };
    }
);
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
                }.property(),

                /**
                 * view элемента формы
                 * @property elementView
                 */
                elementView: function() {
                    var self = this;
                    var elementFactory = self.get('elementFactory');
                    return Ember.View.extend({
                        init: function() {
                            var type = this.get('meta.type');
                            var elementMixin = elementFactory.elementMixinForType(type) || {};
                            this.reopen(elementMixin, UMI.FormSimpleElementValidateMixin);
                            this._super();
                        }
                    });
                }.property()
            });
        };
    }
);
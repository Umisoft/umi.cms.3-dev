define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormElementsMixin = Ember.Mixin.create({
                /**
                 * Фабрика элементов
                 * @property elementFactory
                 */
                elementFactory: function() {
                    return UMI.FormElementFactory.create({
                        container: this.container
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
                            this.reopen(elementMixin);
                            this._super();
                        }
                    });
                }.property()
            });
        };
    }
);
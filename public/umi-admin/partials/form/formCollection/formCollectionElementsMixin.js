define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormCollectionElementsMixin = Ember.Mixin.create(UMI.FormElementsMixin, {
                /**
                 * Фабрика элементов
                 * @property elementFactory
                 */
                elementFactory: function() {
                    return UMI.FormElementFactory.create({
                        container: this.container,

                        fieldset: 'FormFieldsetCollectionElement',

                        wysiwyg: 'FormHtmlEditorCollectionElement',

                        select: 'FormSelectCollectionElement',

                        checkbox: 'FormCheckboxCollectionElement',

                        checkboxGroup: 'FormCheckboxGroupCollectionElement',

                        permissions: 'FormPermissionsElement',

                        objectRelation: 'FormObjectRelationElement',

                        pageRelation: 'FormObjectRelationElement'
                    });
                }.property(),

                elementFactoryForDataSource: function() {
                    return UMI.FormElementFactory.create({
                        container: this.container,

                        slug: 'FormSlugCollectionElement'
                    });
                }.property(),

                /**
                 * view элемента формы
                 * @property elementView
                 */
                elementView: function() {
                    var self = this;
                    var elementFactory = self.get('elementFactory');
                    var elementFactoryForDataSource = self.get('elementFactoryForDataSource');

                    return Ember.View.extend({
                        init: function() {
                            var meta = this.get('meta');
                            var type = Ember.get(meta, 'type');
                            var dataSource = Ember.get(meta, 'dataSource');
                            var elementMixin;

                            if (dataSource) {
                                elementMixin = elementFactoryForDataSource.elementMixinForType(dataSource, true);
                            }

                            if (!elementMixin) {
                                elementMixin = elementFactory.elementMixinForType(type) || {};
                            }

                            if (type !== 'fieldset') {
                                this.reopen(elementMixin, UMI.FormCollectionElementValidateMixin);
                            } else {
                                this.reopen(elementMixin);
                            }
                            this._super();
                        }
                    });
                }.property()
            });
        };
    }
);
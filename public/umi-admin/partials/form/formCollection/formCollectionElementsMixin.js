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
                }.property()
            });
        };
    }
);
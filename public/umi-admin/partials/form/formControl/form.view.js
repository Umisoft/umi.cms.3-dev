define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Фабрика элементов
                 * @property elementFactory
                 */
                elementFactory: function() {
                    return UMI.FormElementFactory.create({
                        container: this.container,

                        wysiwyg: 'FormHtmlEditorCollectionElement',

                        select: 'FormSelectCollectionElement',

                        checkbox: 'FormCheckboxCollectionElement',

                        checkboxGroup: 'FormCheckboxGroupCollectionElement',

                        permissions: 'FormPermissionsElement',

                        objectRelation: 'FormObjectRelationElement',

                        pageRelation: 'FormObjectRelationElement'
                    });
                }.property(),

                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                willDestroyElement: function() {
                    this.get('controller').removeObserver('object.validErrors.@each');
                }
            });
        };
    }
);
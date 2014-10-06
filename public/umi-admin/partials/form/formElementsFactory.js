define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormElementFactory = Ember.Object.extend({
                elementMixinForType: function(type) {
                    var elementMixinName = this.get(type);

                    if (elementMixinName) {
                        var elementMixin = this.container.lookupFactory('mixin:' + elementMixinName);

                        if (elementMixin) {
                            return elementMixin;
                        }
                    } else {
                        Ember.warn('Mixin for element with type "' + type + '" not registered');
                    }
                },

                checkbox: 'FormCheckboxElement',

                checkboxGroup: 'FormCheckboxGroupElement',

                color: 'FormColorElement',

                date: 'FormDateElement',

                datetime: 'FormDateTimeElement',

                email: 'FormEmailElement',

                fieldset: 'FormFieldsetElement',

                file: 'FormFileElement',

                hidden: 'FormHiddenElement',

                image: 'FormImageElement',

                multiSelect: 'FormMultiSelectElement',

                number: 'FormNumberElement',

                password: 'FormPasswordElement',

                passwordWithConfirmation: 'FormPasswordWithConfirmationElement',

                radio: 'FormRadioElement',

                select: 'FormSelectElement',

                submit: 'FormSubmitElement',

                text: 'FormTextElement',

                textarea: 'FormTextareaElement',

                time: 'FormTimeElement',

                wysiwyg: 'FormHtmlEditorElement'
            });
        };
    }
);
define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {

            UMI.FormElementMixin = Ember.Mixin.create({
                classNames: ['umi-columns', 'large-4', 'small-12'],

                classNameBindings: ['isError:error'],

                /**
                 * @property isError
                 * @hook
                 */
                isError: function() {
                    var errors = this.get('meta.errors');
                    if (Ember.typeOf(errors) === 'array' && errors.length) {
                        return errors.join('. ');
                    }
                }.property('meta.errors.@each'),

                isRequired: function() {
                    var validators = this.get('meta.validators');
                    if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                        return ' *';
                    }
                }.property('meta.validators'),

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}' +
                '{{view.isRequired}}</span></div>{{yield}}')
            });

            UMI.FormCollectionElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
                isError: function() {
                    var dataSource = this.get('meta.dataSource');
                    var isValid = !!this.get('object.validErrors.' + dataSource);

                    return isValid;
                }.property('object.validErrors.@each'),

                isRequired: function() {
                    var object = this.get('object');
                    var dataSource = this.get('meta.dataSource');
                    var validators;
                    if (object) {
                        validators = this.get('object').validatorsForProperty(dataSource);
                        if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                            return ' *';
                        }
                    }
                }.property('object')
            });

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

                dateTime: 'FormDateTimeElement',

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
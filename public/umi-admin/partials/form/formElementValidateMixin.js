define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormElementValidateMixin = Ember.Mixin.create({
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

                /**
                 * @property isRequired
                 * @hook
                 */
                isRequired: function() {
                    var validators = this.get('meta.validators');
                    if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                        return ' *';
                    }
                }.property('meta.validators')
            });

            UMI.ElementValidateMixin = Ember.Mixin.create({
                /**
                 * Определяет тип валидатора
                 * @property validatorType
                 * @optional
                 */
                validatorType: null,

                /**
                 * Метод вызывается при необходимости валидации поля
                 * @method sendValidate
                 * @optional
                 */
                checkValidate: function() {},

                focusOut: function() {//TODO: Вынести тригеры из миксина. Удобнее будет управлять валидацией непосредственно из элемента
                    this.validate();
                },

                /**
                 * Метод вызывается для отмены валидатора
                 */
                clearValidate: function() {},

                focusIn: function() {
                    this.clearValidateError();
                },

                validate: function() {
                    var self = this;
                    var validatorType = this.get('validatorType');
                    var property;
                    var meta = self.get('meta');
                    var validationError;

                    if (validatorType === 'collection') {
                        property = Ember.get(meta, 'dataSource');
                        var object = self.get('object');
                        object.filterProperty(property);
                        object.validateProperty(property);
                    } else {
                        UMI.validator.filterProperty(Ember.get(meta, 'value'), Ember.get(meta, 'filters'));
                        validationError = UMI.validator.validateProperty(Ember.get(meta, 'value'), Ember.get(meta, 'validators'));
                        validationError = validationError || [];
                        Ember.set(meta, 'errors', validationError);
                    }
                },

                clearValidateError: function() {
                    var self = this;
                    var meta = self.get('meta');
                    var dataSource = Ember.get(meta, 'dataSource');

                    if (self.get('validatorType') === 'collection') {
                        var object = self.get('object');
                        object.clearValidateForProperty(dataSource);
                    } else {
                        Ember.set(meta, 'errors', []);
                    }
                },

                validateErrorsTemplate: function() {
                    var validatorType = this.get('validatorType');
                    var template;
                    var propertyName;

                    if (validatorType === 'collection') {
                        propertyName = this.get('meta.dataSource');
                        template = '{{#if view.object.validErrors.' + propertyName + '}}' +
                        '<small class="error">{{view.object.validErrors.' + propertyName + '}}</small>' +
                        '{{/if}}';
                    } else {
                        template = '{{#if view.parentView.isError}}<small class="error">{{view.parentView.isError}}</small>{{/if}}';
                    }

                    return template;
                }
            });
        };
    }
);
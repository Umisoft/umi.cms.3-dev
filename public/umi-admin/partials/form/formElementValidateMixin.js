define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormElementValidateMixin = Ember.Mixin.create({
                classNameBindings: ['validateErrors:error'],

                /**
                 * Вычисляемое свойство возвращает сообщение ошибок валидации
                 * @property validateErrors
                 * @hook
                 * @return {String|Null}
                 */
                validateErrors: function() {
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

            UMI.FormElementValidatable = Ember.Mixin.create({
                /**
                 * Определяет тип валидатора
                 * @property validatorType
                 * @optional
                 */
                validatorType: null,

                /**
                 * Метод вызывается при необходимости валидации поля
                 * @method checkValidate
                 */
                checkValidate: function() {
                    this._validate();
                },

                /**
                 * Метод вызывается для отмены валидатора
                 * @method clearValidate
                 */
                clearValidate: function() {
                    this._clearValidateError();
                },

                isWrapperTemplate: false,

                /**
                 * Метод возвращает шаблон ошибок валидации для поля объекта
                 * @method validateErrorsTemplate
                 * @return {String} template
                 */
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
                        if (this.get('isWrapperTemplate')) {
                            template = '{{#if view.validateErrors}}' +
                            '<small class="error">{{view.validateErrors}}</small>{{/if}}';
                        } else {
                            template = '{{#if view.parentView.validateErrors}}' +
                            '<small class="error">{{view.parentView.validateErrors}}</small>{{/if}}';
                        }
                    }

                    return template;
                },

                /**
                 * Валидирует поле
                 * @method validate
                 * @private
                 */
                _validate: function() {
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

                /**
                 * Метод очищает ошибки валидации для данного поля
                 * @method clearValidateError
                 * @private
                 */
                _clearValidateError: function() {
                    var self = this;
                    var meta = self.get('meta');
                    var dataSource = Ember.get(meta, 'dataSource');

                    if (self.get('validatorType') === 'collection') {
                        var object = self.get('object');
                        object.clearValidateForProperty(dataSource);
                    } else {
                        Ember.set(meta, 'errors', []);
                    }
                }
            });
        };
    }
);
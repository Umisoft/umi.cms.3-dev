define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormElementValidateMixin = Ember.Mixin.create({
                classNameBindings: ['validateErrors:error'],

                /**
                 * @abstract
                 */
                elementView: null,

                elementTemplate: '{{view view.elementView object=view.object meta=view.meta}}',

                template: function() {
                    var elementView = this.get('elementTemplate');
                    var validate = this.validateErrorsTemplate();
                    var template = elementView + validate;
                    return Ember.Handlebars.compile(template);
                }.property(),

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

                /**
                 * Метод возвращает шаблон ошибок валидации для поля объекта
                 * @method validateErrorsTemplate
                 * @return {String} template
                 */
                validateErrorsTemplate: function() {
                    return '{{#if view.validateErrors}}<small class="error">{{view.validateErrors}}</small>{{/if}}';
                },

                /**
                 * Валидирует поле
                 * @method validate
                 * @private
                 */
                _validate: function() {
                    var self = this;
                    var meta = self.get('meta');
                    var validationError;

                    UMI.validator.filterProperty(Ember.get(meta, 'value'), Ember.get(meta, 'filters'));
                    validationError = UMI.validator.validateProperty(Ember.get(meta, 'value'), Ember.get(meta, 'validators'));
                    validationError = validationError || [];
                    Ember.set(meta, 'errors', validationError);
                },

                /**
                 * Метод очищает ошибки валидации для данного поля
                 * @method clearValidateError
                 * @private
                 */
                _clearValidateError: function() {
                    var self = this;
                    var meta = self.get('meta');

                    Ember.set(meta, 'errors', []);
                },

                init: function() {
                    this.super();
                }
            });

            UMI.FormElementValidateHandlerMixin = Ember.Mixin.create({
                focusOut: function() {
                    var parentView = this.get('parentView');
                    if (Ember.canInvoke(parentView, 'checkValidate')) {
                        parentView.checkValidate();
                    }
                },

                focusIn: function() {
                    var parentView = this.get('parentView');
                    if (Ember.canInvoke(parentView, 'clearValidate')) {
                        parentView.clearValidate();
                    }
                }
            });
        };
    }
);
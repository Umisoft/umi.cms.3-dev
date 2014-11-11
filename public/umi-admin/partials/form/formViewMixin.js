define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormViewMixin = Ember.Mixin.create({
                /**
                 * Тип DOM элемента
                 * @property tagName
                 * @type String
                 * @default "form"
                 */
                tagName: 'form',

                submit: function() {
                    return false;
                },

                /**
                 * Элементы формы
                 * @property formElements
                 */
                formElements: Ember.computed.alias('controller.formElements'),

                /**
                 * Проверяет наличие fieldset
                 * @method hasFieldset
                 * @return bool
                 */
                hasFieldset: function() {
                    var formElements = this.get('formElements') || [];
                    return formElements.isAny('type', 'fieldset');
                }.property('formElements.@each'),

                /**
                 * Элементы с типом fieldset
                 * @property fieldsetElements
                 */
                fieldsetElements: function() {
                    return this.get('formElements').filterBy('type', 'fieldset');
                }.property('formElements.@each.type')
            });
        };
    }
);
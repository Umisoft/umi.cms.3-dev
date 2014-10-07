define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormPasswordWithConfirmationElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "passwordWithConfirmationElement" object=view.object meta=view.meta}}')
        });

        UMI.PasswordWithConfirmationElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],

            type: 'text',

            password: null,

            passwordConfirm: null,

            confirmLabel: 'Повторите новый пароль',

            template: function() {
                var inputTemplate =
                    '{{input typeBinding="view.type" value=view.password name=view.meta.attributes.name}}';
                var inputConfirmTemplate =
                    '<div><span class="umi-form-label">{{view.confirmLabel}}</span></div>' +
                    '{{input typeBinding="view.type" value=view.passwordConfirm name=view.meta.attributes.name}}';

                this.set('validatorType', null);
                var validate = this.validateErrorsTemplate();
                var template = inputTemplate + inputConfirmTemplate + validate;

                return Ember.Handlebars.compile(template);
            }.property()
        });
    };
});
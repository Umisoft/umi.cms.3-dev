define(['App', 'text!./passwordElement.hbs'], function(UMI, passwordElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/password-element'] = Ember.Handlebars.compile(passwordElement);

    return function(){
        UMI.PasswordElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-password'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });
            }
        });
    };
});
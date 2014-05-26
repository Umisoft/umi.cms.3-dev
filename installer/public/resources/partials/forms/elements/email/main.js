define(['App', 'text!./emailElement.hbs'], function(UMI, emailElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/email-element'] = Ember.Handlebars.compile(emailElement);

    return function(){
        UMI.EmailElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-email'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });
            },

            inputView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});
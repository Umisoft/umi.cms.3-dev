define(['App', 'text!./textElement.hbs'], function(UMI, textElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/text-element'] = Ember.Handlebars.compile(textElement);

    return function(){
        UMI.TextElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-text'],

            didInsertElement: function(){
                var that = this;
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                    that.focusIn();
                });
            }
        });
    };
});
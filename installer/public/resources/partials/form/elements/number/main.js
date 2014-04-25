define(['App', 'text!./numberElement.hbs'], function(UMI, numberElement){
    "use strict";

    Ember.TEMPLATES['UMI/components/number-element'] = Ember.Handlebars.compile(numberElement);

    return function(){
        UMI.NumberElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-number'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });
            }
        });
    };
});
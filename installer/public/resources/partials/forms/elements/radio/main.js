define(['App', 'text!./radioElement.hbs'], function(UMI, radioElement){
    "use strict";
    Ember.TEMPLATES['UMI/components/radio-element'] = Ember.Handlebars.compile(radioElement);

    return function(){
        UMI.RadioElementComponent = Ember.Component.extend({
            classNames: ['umi-element-radio'],

            inputId: function(){
                return 'input-' + this.get('elementId');
            }.property()
        });
    };
});
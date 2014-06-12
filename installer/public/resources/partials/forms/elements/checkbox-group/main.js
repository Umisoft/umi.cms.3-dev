define(['App', 'text!./checkboxGroupElement.hbs'], function(UMI, checkboxGroupElement){
    "use strict";

    return function(){
        UMI.CheckboxGroupElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(checkboxGroupElement),
            classNames: ['umi-element-checkbox-group']
        });
    };
});
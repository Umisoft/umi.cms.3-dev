define(['App', 'text!./checkboxGroupElement.hbs', 'text!./checkboxGroupCollectionElement.hbs'], function(UMI, checkboxGroupElement, checkboxGroupCollectionElement){
    "use strict";

    return function(){
        UMI.CheckboxGroupElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(checkboxGroupElement),
            classNames: ['umi-element-checkbox-group']
        });

        UMI.CheckboxGroupCollectionElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(checkboxGroupCollectionElement),
            classNames: ['umi-element-checkbox-group'],
            checkboxElementView: UMI.CheckboxElementView.extend({
                setCheckboxValue: function(){},
                actions: {
                    change: function(){
                        var self = this;
                        var $el = this.$();

                    }
                }
            })
        });
    };
});
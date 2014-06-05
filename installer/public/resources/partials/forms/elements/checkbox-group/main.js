define(['App', 'text!./checkboxGroupElement.hbs'], function(UMI, checkboxGroupElement){
    "use strict";

    Ember.TEMPLATES['UMI/checkbox-group-element'] = Ember.Handlebars.compile(checkboxGroupElement);

    return function(){
        UMI.CheckboxGroupElementView = Ember.View.extend({
            templateName: 'checkbox-group-element',
            classNames: ['umi-element-checkbox-group'],
            selectedIds: [],

            check: null,

            changeValue: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');

                var value;
                object.set(property, value);
            }.observes('check'),


            didInsertElement: function(){
                var that = this;
                $('#' + this.get('elementId')).find('input').change(function(){
                    that.set('check', !that.get('check'));
                });
            }
        });
    };
});
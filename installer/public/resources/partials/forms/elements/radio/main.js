define(['App', 'text!./radioElement.hbs'], function(UMI, radioElement){
    "use strict";
    Ember.TEMPLATES['UMI/radio-element'] = Ember.Handlebars.compile(radioElement);

    return function(){
        UMI.RadioElementView = Ember.View.extend({
            templateName: 'radio-element',
            classNames: ['umi-element-radio'],

            didInsertElement: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');
            }
        });
    };
});
define(
    ['App', 'text!./form.hbs'], function(UMI, form){
    'use strict';

    return function(){

        UMI.FormControlCreateController = UMI.FormControlController.extend({});

        UMI.FormControlCreateView = UMI.FormControlView.extend({
            layout: Ember.Handlebars.compile(form)
        });
    };
});
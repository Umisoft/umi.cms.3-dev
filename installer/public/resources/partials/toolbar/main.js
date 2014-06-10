define(['App', './view', 'text!./templates/toolbar.hbs', './buttons/main'], function(UMI, view, toolbarTpl, buttons){
    'use strict';

    Ember.TEMPLATES['UMI/toolbar'] = Ember.Handlebars.compile(toolbarTpl);
    buttons();
    view();
});
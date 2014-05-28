define(['App', './view', 'text!./templates/toolBar.hbs', './buttons/main'], function(UMI, view, toolBarTpl, buttons){
    'use strict';

    Ember.TEMPLATES['UMI/toolBar'] = Ember.Handlebars.compile(toolBarTpl);
    buttons();
    view();
});
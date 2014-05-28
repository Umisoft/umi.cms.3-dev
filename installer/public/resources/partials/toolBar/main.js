define(['App', './view', 'text!./templates/toolBar.hbs'], function(UMI, view, toolBarTpl){
    'use strict';

    Ember.TEMPLATES['UMI/toolBar'] = Ember.Handlebars.compile(toolBarTpl);
    view();
});
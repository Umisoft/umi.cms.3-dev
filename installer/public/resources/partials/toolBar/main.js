define([
    'text!./templates/toolBar.hbs',
    './view',
    'App'
], function(
    toolBarTpl,
    view
){
    'use strict';

    Ember.TEMPLATES['UMI/toolBar'] = Ember.Handlebars.compile(toolBarTpl);
    view();
});
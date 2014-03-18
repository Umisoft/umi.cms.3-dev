define([
    'text!./templates/topBar.hbs',
    './view',
    'App'
], function(topBarTpl, view){
    'use strict';
    Ember.TEMPLATES['UMI/topBar'] = Ember.Handlebars.compile(topBarTpl);
    view();
});
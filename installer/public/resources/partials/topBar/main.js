define([
    'text!./templates/topBar.hbs',
    'App'
], function(topBarTpl){
    'use strict';
    Ember.TEMPLATES['UMI/topBar'] = Ember.Handlebars.compile(topBarTpl);
});
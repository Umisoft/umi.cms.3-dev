define([
    'text!./templates/table.hbs',
    './view',
    'App'
], function(tableTpl, view){
    'use strict';

    Ember.TEMPLATES['UMI/table'] = Ember.Handlebars.compile(tableTpl);
    view();
});
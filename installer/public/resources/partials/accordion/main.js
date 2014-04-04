define([
    'App',
    'text!./templates/accordion.hbs',
    './view'
], function(UMI, accordion, view){
    'use strict';
    Ember.TEMPLATES['UMI/accordion'] = Ember.Handlebars.compile(accordion);
    view();
});
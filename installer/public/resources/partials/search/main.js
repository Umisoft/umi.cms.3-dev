define(['App', 'text!./search.hbs', './view'], function(UMI, searchTpl, view){
    'use strict';
    Ember.TEMPLATES['UMI/search'] = Ember.Handlebars.compile(searchTpl);
    view();
});
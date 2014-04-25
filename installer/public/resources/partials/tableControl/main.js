define([
    'text!./templates/tableControl.hbs',
    './controllers',
    './view',
    'App'
], function(tableControlTpl, controllers, view){
    'use strict';

    Ember.TEMPLATES['UMI/tableControl'] = Ember.Handlebars.compile(tableControlTpl);

    controllers();
    view();
});
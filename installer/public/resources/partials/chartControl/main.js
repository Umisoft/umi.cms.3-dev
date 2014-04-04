define([
    'text!./templates/chartControl.hbs',
    './view',
    'App'
], function(chartControlTpl, view){
    'use strict';

    Ember.TEMPLATES['UMI/chartControl'] = Ember.Handlebars.compile(chartControlTpl);
    view();
});
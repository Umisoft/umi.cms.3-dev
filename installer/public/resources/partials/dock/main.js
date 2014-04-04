define(['text!./dock.hbs', './controllers', './view', 'App'], function(tpl, controller, view){
    'use strict';
    Ember.TEMPLATES['UMI/dock'] = Ember.Handlebars.compile(tpl);
    controller();
    view();
});
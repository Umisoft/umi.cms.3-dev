define([
    'text!./templates/tableControl.hbs',
    'text!./templates/tableControlColumnSelectorPopup.hbs',
    './controllers',
    './view',
    'App'
], function(tableControlTpl, tableControlColumnSelectorPopupTpl, controllers, view){
    'use strict';

    Ember.TEMPLATES['UMI/tableControl'] = Ember.Handlebars.compile(tableControlTpl);
    Ember.TEMPLATES['UMI/tableControlColumnSelectorPopup'] = Ember.Handlebars.compile(tableControlColumnSelectorPopupTpl);
    controllers();
    view();
});
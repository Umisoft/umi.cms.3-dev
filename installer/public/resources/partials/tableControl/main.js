define([
    'text!./templates/tableControl.hbs',
    'text!./templates/tableControlColumnSelectorPopup.hbs',
    './view',
    'App'
], function(tableControlTpl, tableControlColumnSelectorPopupTpl, view){
    'use strict';

    Ember.TEMPLATES['UMI/tableControl'] = Ember.Handlebars.compile(tableControlTpl);
    Ember.TEMPLATES['UMI/tableControlColumnSelectorPopup'] = Ember.Handlebars.compile(tableControlColumnSelectorPopupTpl);
    view();
});
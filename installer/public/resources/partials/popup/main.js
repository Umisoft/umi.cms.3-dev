define([
    'text!./templates/popup.hbs',
    'text!./templates/tableControlColumnSelectorPopup.hbs',
    './view',
    'App'
], function(
    popupTpl,
    tableControlColumnSelectorPopupTpl,
    view
    ){
    'use strict';

    Ember.TEMPLATES['UMI/popup'] = Ember.Handlebars.compile(popupTpl);
    Ember.TEMPLATES['UMI/tableControlColumnSelectorPopup'] = Ember.Handlebars.compile(tableControlColumnSelectorPopupTpl);
    view();
});
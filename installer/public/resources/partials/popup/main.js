define([
    'text!./templates/popup.hbs',
    'text!./templates/fileManagerPopup.hbs',
    'text!./templates/tableControlColumnSelectorPopup.hbs',
    './view',
    'App'
], function(
    popupTpl,
    fileManagerPopup,
    tableControlColumnSelectorPopupTpl,
    view
    ){
    'use strict';

    Ember.TEMPLATES['UMI/popup'] = Ember.Handlebars.compile(popupTpl);
    Ember.TEMPLATES['UMI/fileManagerPopup'] = Ember.Handlebars.compile(fileManagerPopup);
    Ember.TEMPLATES['UMI/tableControlColumnSelectorPopup'] = Ember.Handlebars.compile(tableControlColumnSelectorPopupTpl);
    view();
});
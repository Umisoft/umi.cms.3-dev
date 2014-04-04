define([
    'text!./templates/popup.hbs',
    './view',
    'App'
], function(popupTpl, view){
    'use strict';

    Ember.TEMPLATES['UMI/popup'] = Ember.Handlebars.compile(popupTpl);
    view();
});
define([
    'text!./templates/topBar.hbs',
    'App'
], function(topBarTpl, UMI){
    'use strict';

    UMI.TopBarView = Ember.View.extend({
        template: Ember.Handlebars.compile(topBarTpl)
    });
});
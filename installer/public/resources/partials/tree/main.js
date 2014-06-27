define(
    [
        'App',
        'text!./templates/treeControl.hbs',
        'text!./templates/treeItem.hbs',
        './controllers',
        './views'
    ],
    function(UMI, treeControlTpl, treeItemTpl, controllers, views){
        'use strict';
        Ember.TEMPLATES['UMI/treeControl'] = Ember.Handlebars.compile(treeControlTpl);
        Ember.TEMPLATES['UMI/treeItem'] = Ember.Handlebars.compile(treeItemTpl);
        controllers();
        views();
    }
);
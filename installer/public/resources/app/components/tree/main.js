define(
    [
        'App',
        'text!./treeControl.hbs',
        'text!./treeItem.hbs',
        './controllers',
        './views',
        './elements/contextMenu/main'
    ],
    function(UMI, treeControlTpl, treeItemTpl, controllers, views){
        'use strict';
        Ember.TEMPLATES['UMI/treeControl'] = Ember.Handlebars.compile(treeControlTpl);
        Ember.TEMPLATES['UMI/_treeItem'] = Ember.Handlebars.compile(treeItemTpl);
        controllers();
        views();
    }
);
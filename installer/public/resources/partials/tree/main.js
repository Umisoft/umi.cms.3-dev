define(
    [
        'App',
        'text!./treeControl.hbs',
        'text!./treeItem.hbs',
        'text!./contextMenu.hbs',
        './controllers',
        './views'
    ],
    function(UMI, treeControlTpl, treeItemTpl, contextMenuTpl, controllers, views){
        'use strict';
        Ember.TEMPLATES['UMI/treeControl'] = Ember.Handlebars.compile(treeControlTpl);
        Ember.TEMPLATES['UMI/treeItem'] = Ember.Handlebars.compile(treeItemTpl);
        Ember.TEMPLATES['UMI/treeControlContextMenu'] = Ember.Handlebars.compile(contextMenuTpl);
        controllers();
        views();
    }
);
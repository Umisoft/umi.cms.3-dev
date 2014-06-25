define(
    [
        'App',
        'text!./templates/treeControl.hbs',
        'text!./templates/treeItem.hbs',
        'text!./templates/treeControlContextMenu.hbs',
        './controllers',
        './views'
    ],
    function(UMI, treeControlTpl, treeItemTpl, treeControlContextMenuTpl, controllers, views){
        'use strict';
        Ember.TEMPLATES['UMI/treeControl'] = Ember.Handlebars.compile(treeControlTpl);
        Ember.TEMPLATES['UMI/treeItem'] = Ember.Handlebars.compile(treeItemTpl);
        Ember.TEMPLATES['UMI/treeControlContextMenu'] = Ember.Handlebars.compile(treeControlContextMenuTpl);
        controllers();
        views();
    }
);
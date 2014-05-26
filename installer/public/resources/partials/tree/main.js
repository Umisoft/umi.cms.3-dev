define(
    [
        'App',
        'text!./treeControl.hbs',
        'text!./treeItem.hbs',
        'text!./treeControlContextMenu.hbs', //Гораздо удобнее искать шаблоны если они совпадают с именами файлов
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
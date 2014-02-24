define([
    'text!./templates/table.hbs',
    './view'
],
    function (tableTpl, view) {
        'use strict';

        Ember.TEMPLATES['UMI/tableControl'] = Ember.Handlebars.compile(tableTpl);
        view();
    }
);
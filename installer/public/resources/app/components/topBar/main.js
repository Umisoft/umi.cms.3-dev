define(['text!./templates/topBar.hbs'],
    function (topBarTpl) {
        'use strict';
        Ember.TEMPLATES['UMI/topBar'] = Ember.Handlebars.compile(topBarTpl);
    }
);
define(['text!./dock.hbs', './models', './controllers', './view'],
    function (tpl, models, controller, view) {
        'use strict';
        Ember.TEMPLATES['UMI/dock'] = Ember.Handlebars.compile(tpl);
        models();
        controller();
        view();
    }
);
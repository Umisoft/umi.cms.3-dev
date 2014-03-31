define([
    'App',
    'text!./yandexWebmaster.hbs',
    './view'
], function(
        UMI,
        yandexWebmasterTpl,
        view
    ){
    'use strict';
    Ember.TEMPLATES['UMI/seoYandexWebmaster'] = Ember.Handlebars.compile(yandexWebmasterTpl);
    view();
});
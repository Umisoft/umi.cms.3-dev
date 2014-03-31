define([
    'App',
    'text!./seoMegaIndex.hbs',
    './view'
], function(
        UMI,
        seoMegaIndexTpl,
        view
    ){
    'use strict';
    Ember.TEMPLATES['UMI/seoMegaIndex'] = Ember.Handlebars.compile(seoMegaIndexTpl);
    view();
});
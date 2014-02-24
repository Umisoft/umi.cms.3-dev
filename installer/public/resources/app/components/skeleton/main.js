define([
    './app', './templates', './models', './router', './controllers', './views'
], function(applicationNamespace, templates, models, router, controller, views){
    'use strict';
    templates();
    models();
    router();
    controller();
    views();
    return applicationNamespace;
});
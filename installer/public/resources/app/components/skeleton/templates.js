define([
    'text!./templates/application.hbs',
    'text!./templates/component.hbs',
    'text!./templates/actions/error.hbs',
    'text!./templates/actions/children.hbs',
    'text!./templates/actions/form.hbs',
    'text!./templates/actions/files.hbs',
    'text!./templates/actions/chart.hbs',
    'text!./templates/actions/tree.hbs',
    'text!./templates/actions/counters.hbs'
], function(applicationTpl, componentTpl, errorTpl, childrenTpl, formTpl, filesTpl, chartTpl, treeTpl, countersTpl){
    'use strict';
    return function(){
        Ember.TEMPLATES['UMI/application'] = Ember.Handlebars.compile(applicationTpl);
        Ember.TEMPLATES['UMI/component'] = Ember.Handlebars.compile(componentTpl);
        ///Ember.TEMPLATES['UMI/error'] = Ember.Handlebars.compile(errorTpl);
        Ember.TEMPLATES['UMI/children'] = Ember.Handlebars.compile(childrenTpl);
        Ember.TEMPLATES['UMI/form'] = Ember.Handlebars.compile(formTpl);
        Ember.TEMPLATES['UMI/filter'] = Ember.Handlebars.compile(childrenTpl);
        Ember.TEMPLATES['UMI/fileManager'] = Ember.Handlebars.compile(filesTpl);
        Ember.TEMPLATES['UMI/chart'] = Ember.Handlebars.compile(chartTpl);
        Ember.TEMPLATES['UMI/tree'] = Ember.Handlebars.compile(treeTpl);
        Ember.TEMPLATES['UMI/counters'] = Ember.Handlebars.compile(countersTpl);
    };
});
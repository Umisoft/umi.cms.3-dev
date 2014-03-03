define([
    'text!./templates/application.hbs', 'text!./templates/component.hbs',
    'text!./templates/error.hbs', 'text!./templates/children.hbs', 'text!./templates/editor.hbs'
], function(applicationTpl, componentTpl, errorTpl, childrenTpl, editorTpl){
    'use strict';
    return function(){
        Ember.TEMPLATES['UMI/application'] = Ember.Handlebars.compile(applicationTpl);
        Ember.TEMPLATES['UMI/component'] = Ember.Handlebars.compile(componentTpl);
        ///Ember.TEMPLATES['UMI/error'] = Ember.Handlebars.compile(errorTpl);
        Ember.TEMPLATES['UMI/children'] = Ember.Handlebars.compile(childrenTpl);
        Ember.TEMPLATES['UMI/editor'] = Ember.Handlebars.compile(editorTpl);
        Ember.TEMPLATES['UMI/filter'] = Ember.Handlebars.compile('<h2>Filter</h2>');
    };
});
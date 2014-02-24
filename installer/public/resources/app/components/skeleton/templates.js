define(
    [
        'text!./templates/application.hbs',
        'text!./templates/component.hbs',
        'text!./templates/modeList.hbs',
        'text!./templates/error.hbs',
        'text!./templates/table.hbs',
        'text!./templates/form.hbs'
    ],
    function (applicationTpl, componentTpl, modeListTpl, errorTpl, tableTpl, formTpl) {
        'use strict';
        return function () {
            Ember.TEMPLATES['UMI/application'] = Ember.Handlebars.compile(applicationTpl);
            Ember.TEMPLATES['UMI/component'] = Ember.Handlebars.compile(componentTpl);
            Ember.TEMPLATES['UMI/componentMode'] = Ember.Handlebars.compile(modeListTpl);
            ///Ember.TEMPLATES['UMI/error'] = Ember.Handlebars.compile(errorTpl);
            Ember.TEMPLATES['UMI/tableTemplate'] = Ember.Handlebars.compile(tableTpl);
            Ember.TEMPLATES['UMI/formTemplate'] = Ember.Handlebars.compile(formTpl);
        };
    }
);
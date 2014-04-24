define([
    'text!./templates/application.hbs',
    'text!./templates/component.hbs',
    'text!./templates/errors/errors.hbs',

    /* TODO Удалить после рефакторинга лишних шаблонов */
        'text!./templates/actions/children.hbs',
        'text!./templates/actions/form.hbs',
        'text!./templates/actions/files.hbs',
        'text!./templates/actions/tree.hbs',
        'text!./templates/actions/counters.hbs',
        'text!./templates/actions/counter.hbs',
        'text!./templates/actions/megaIndex.hbs',
        'text!./templates/actions/yandexWebmaster.hbs',
    'text!./templates/actions/createForm.hbs',

], function(
    applicationTpl,
    componentTpl,
    errorsTpl,

    /* TODO Удалить после рефакторинга лишних шаблонов */
        childrenTpl,
        formTpl,
        filesTpl,
        treeTpl,
        countersTpl,
        counterTpl,
        megaIndexTpl,
        yandexWebmasterTpl,
        createFormTpl
    ){
    'use strict';
    return function(){
        Ember.TEMPLATES['UMI/error'] = Ember.Handlebars.compile(errorsTpl);
        Ember.TEMPLATES['UMI/module/error'] = Ember.Handlebars.compile(errorsTpl);
        Ember.TEMPLATES['UMI/component/error'] = Ember.Handlebars.compile(errorsTpl);
        Ember.TEMPLATES['UMI/application'] = Ember.Handlebars.compile(applicationTpl);
        Ember.TEMPLATES['UMI/component'] = Ember.Handlebars.compile(componentTpl);

        /* TODO Удалить после рефакторинга лишних шаблонов */
            Ember.TEMPLATES['UMI/children'] = Ember.Handlebars.compile(childrenTpl);
            Ember.TEMPLATES['UMI/form'] = Ember.Handlebars.compile(formTpl);
            Ember.TEMPLATES['UMI/filter'] = Ember.Handlebars.compile(childrenTpl);
            Ember.TEMPLATES['UMI/fileManager'] = Ember.Handlebars.compile(filesTpl);
            Ember.TEMPLATES['UMI/tree'] = Ember.Handlebars.compile(treeTpl);
            Ember.TEMPLATES['UMI/counters'] = Ember.Handlebars.compile(countersTpl);
            Ember.TEMPLATES['UMI/counter'] = Ember.Handlebars.compile(counterTpl);
            Ember.TEMPLATES['UMI/megaindexReport'] = Ember.Handlebars.compile(megaIndexTpl);
            Ember.TEMPLATES['UMI/yandexWebmasterReport'] = Ember.Handlebars.compile(yandexWebmasterTpl);
            Ember.TEMPLATES['UMI/createForm'] = Ember.Handlebars.compile(createFormTpl);
    };
});
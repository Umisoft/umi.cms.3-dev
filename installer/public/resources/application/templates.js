define(
    [
        'text!./templates/application.hbs',
        'text!./templates/component.hbs',
        'text!./templates/errors/errors.hbs',
        'text!./templates/sidebar/tree.hbs',
        'text!./templates/sidebar/menu.hbs',
        'text!./templates/content/filter.hbs',
        'text!./templates/content/editForm.hbs',
        'text!./templates/content/simpleForm.hbs',
        'text!./templates/content/files.hbs',
        'text!./templates/content/counters.hbs',
        'text!./templates/content/counter.hbs',
        'text!./templates/content/megaIndex.hbs',
        'text!./templates/content/yandexWebmaster.hbs'


    ], function(
        applicationTpl,
        componentTpl,
        errorsTpl,
        treeTpl,
        menuTpl,
        filterTpl,
        editFormTpl,
        simpleFormTpl,
        filesTpl,
        countersTpl,
        counterTpl,
        megaIndexTpl,
        yandexWebmasterTpl
    ){
        'use strict';

        return function(){
            Ember.TEMPLATES['UMI/application'] = Ember.Handlebars.compile(applicationTpl);
            Ember.TEMPLATES['UMI/component'] = Ember.Handlebars.compile(componentTpl);

            Ember.TEMPLATES['UMI/errors'] =  Ember.TEMPLATES['UMI/error'] = Ember.Handlebars.compile(errorsTpl);
            Ember.TEMPLATES['UMI/module/errors'] = Ember.TEMPLATES['UMI/module/error'] =  Ember.Handlebars.compile(errorsTpl);
            Ember.TEMPLATES['UMI/component/errors'] = Ember.TEMPLATES['UMI/component/error'] = Ember.Handlebars.compile(errorsTpl);

            Ember.TEMPLATES['UMI/tree'] = Ember.Handlebars.compile(treeTpl);
            Ember.TEMPLATES['UMI/menu'] = Ember.Handlebars.compile(menuTpl);

            Ember.TEMPLATES['UMI/filter'] = Ember.Handlebars.compile(filterTpl);
            Ember.TEMPLATES['UMI/editForm'] = Ember.Handlebars.compile(editFormTpl);
            Ember.TEMPLATES['UMI/createForm'] = Ember.Handlebars.compile(editFormTpl);
            Ember.TEMPLATES['UMI/simpleForm'] = Ember.Handlebars.compile(simpleFormTpl);

            Ember.TEMPLATES['UMI/fileManager'] = Ember.Handlebars.compile(filesTpl);

            Ember.TEMPLATES['UMI/counters'] = Ember.Handlebars.compile(countersTpl);
            Ember.TEMPLATES['UMI/counter'] = Ember.Handlebars.compile(counterTpl);
            Ember.TEMPLATES['UMI/megaindexReport'] = Ember.Handlebars.compile(megaIndexTpl);
            Ember.TEMPLATES['UMI/yandexWebmasterReport'] = Ember.Handlebars.compile(yandexWebmasterTpl);
        };
    }
);
define(
    [
        'App',
        'text!./form.hbs',
        'app/components/form/elements/select/main',
        'app/components/form/elements/multiSelect/main',
        'app/components/form/elements/datePicker/main',
        'app/components/form/elements/htmlEditor/main'
    ],
    function(UMI, formTpl){
    'use strict';

    Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

    UMI.FormControlController = Ember.ObjectController.extend({
        hasFieldsets: function(){
            return this.get('content.viewSettings.form.elements').isAny('type', 'fieldset');
        }.property()
    });

    UMI.FormElementController = Ember.ObjectController.extend({
        isFieldset: function(){
            return this.get('content.type') === 'fieldset';
        }.property()
    });

    UMI.FormControlView = Ember.View.extend({
        tagName: 'form',
        templateName: 'formControl',
        classNameBindings: ['class:data.class'],
        attributeBindings: ['abide:data-abide'],
        abide: 'ajax'
    });

    UMI.FieldView = Ember.View.extend({
        classNames: ['umi-columns'],
        classNameBindings: ['wide'],
        wide: function(){
            return this.get('meta.type') === 'wysiwyg' ? 'small-12' : 'large-4 medium-12';
        }.property('meta.type'),
        layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{label}}</label></div>{{yield}}'),
        template: function(){
            var meta = this.get('meta');
            var template;

            switch(meta.type){
                case 'text':
                    template = Ember.Handlebars.compile('{{input type="text" value=object.' + meta.dataSource + ' placeholder=placeholder}}');
                    break;
                case 'textarea':
                    template = Ember.Handlebars.compile('{{textarea value=object.' + meta.dataSource + ' placeholder=meta.placeholder}}');
                    break;
                case 'wysiwyg':
                    template = Ember.Handlebars.compile('{{html-editor object=object property="' + meta.dataSource + '"}}');
                    break;
                case 'datetime':
                    template = Ember.Handlebars.compile('{{date-picker object=object property="' + meta.dataSource + '"}}');
                    break;
                case 'number':
                    template = Ember.Handlebars.compile('{{input type="number" value=object.' + meta.dataSource + '}}');
                    break;
                case 'checkbox':
                    template = Ember.Handlebars.compile('{{input type="checkbox" checked=object.' + meta.dataSource + ' name=name}}<label for="' + meta.name + '"></label>');
                    break;
                case 'select':
                    template = Ember.Handlebars.compile('{{view "select" object=object meta=this}}');
                    break;
                case 'multi-select':
                    template = Ember.Handlebars.compile('{{view "multiSelect" object=object meta=this}}');
                    break;
                case 'file':
                    template = Ember.Handlebars.compile('<div class="umi-input-wrapper-file">{{input type="file" class="umi-file" value=object.' + meta.dataSource + '}}<i class="icon icon-cloud"></i></div>');
                    break;
                default:
                    template = Ember.Handlebars.compile('<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>');
                    break;
            }
            return template;
        }.property('object', 'meta')
    });
    //TODO: Для форм нужно не забыть в шаблоне, и в остальных местах биндить все возможные атрибуты

    //TODO: Кнопка ни как не связана с формой- можно вынести в отдельный компонент
    UMI.SaveButtonView = Ember.View.extend({
        tagName: 'button',
        classNameBindings: ['model.isDirty::disabled'],
        click: function(event){
            if(this.get('model.isDirty')){
                var button = this.$();
                var model = this.get('model');
                button.addClass('loading');
                var params = {
                    object: model,
                    handler: button[0]
                };
                this.get('controller').send('save', params);
            }
        }
    });
});
define(
    [
        'App',
        'text!./form.hbs',
        './mixin',
        './elements/input/main',
        './elements/textarea/main',
        './elements/htmlEditor/main',
        './elements/select/main',
        './elements/multiSelect/main',
        './elements/datepicker/main',
        './elements/magellan/main'
    ],
    function(UMI, formTpl, mixin, inputElement, textareaElement, htmlEditorElement, selectElement, multiSelectElement, datepickerElement, magellanElement){
        'use strict';

        Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

        mixin();
        inputElement();
        textareaElement();
        htmlEditorElement();
        selectElement();
        multiSelectElement();
        datepickerElement();
        magellanElement();

        UMI.FormControlController = Ember.ObjectController.extend({
            hasFieldset: function(){
                return this.get('content.viewSettings.form.elements').isAny('type', 'fieldset');
            }.property()
        });

        UMI.FormElementController = Ember.ObjectController.extend({
            isFieldset: function(){
                return this.get('content.type') === 'fieldset';
            }.property(),
            isExpanded: true,
            actions: {
                expand: function(){
                    this.toggleProperty('isExpanded');
                }
            }
        });

        UMI.FormControlView = Ember.View.extend({
            tagName: 'form',
            templateName: 'formControl',
            classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],
            submit: function(){
                return false;
            }
        });

        UMI.FieldView = Ember.View.extend({
            classNames: ['umi-columns'],
            classNameBindings: ['wide', 'isError:error'],
            isError: function(){
                var meta = this.get('meta');
                return !!this.get('object.validErrors.' + meta.dataSource);
            }.property('object.validErrors'),
            wide: function(){
                return this.get('meta.type') === 'wysiwyg' ? 'small-12' : 'large-4 small-12';
            }.property('meta.type'),
            layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{label}}</label></div>{{yield}}'),
            template: function(){
                var meta = this.get('meta');
                var template;

                switch(meta.type){
                    case 'text':
                        template = '{{input type="text" value=object.' + meta.dataSource + ' placeholder=placeholder validator="collection" dataSource=dataSource}}';
                        break;
                    case 'textarea':
                        template = '{{textarea value=object.' + meta.dataSource + ' placeholder=meta.placeholder validator="collection" dataSource=dataSource}}';
                        break;
                    case 'wysiwyg':
                        template = '{{html-editor object=object property="' + meta.dataSource + '" validator="collection" dataSource=dataSource}}';
                        break;
                    case 'number': // TODO: Поле типа "number" в firefox не работает
                        template = '{{input type="number" value=object.' + meta.dataSource + ' validator="collection" dataSource=dataSource}}';
                        break;
                    case 'checkbox':
                        template = '{{input type="checkbox" checked=object.' + meta.dataSource + ' name=name validator="collection" dataSource=dataSource}}<label for="' + meta.name + '"></label>';
                        break;
                    case 'select':
                        template = '{{view "select" object=object meta=this}}';
                        break;
                    case 'multi-select':
                        template = '{{view "multiSelect" object=object meta=this}}';
                        break;
                    case 'datetime':
                        template = '{{date-picker object=object property="' + meta.dataSource + '"}}';
                        break;
                    case 'file':
                        template = '<div class="umi-input-wrapper-file">{{input type="file" class="umi-file" value=object.' + meta.dataSource + '}}<i class="icon icon-cloud"></i></div>';
                        break;
                    default:
                        template = '<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>';
                        break;
                }
                template += '{{#if object.validErrors.' + meta.dataSource + '}}' + '<small class="error">' + '   {{#each error in object.validErrors.' + meta.dataSource + '}}' + '       {{error.message}}' + '   {{/each}}' + '</small>' + '{{/if}}';
                template = Ember.Handlebars.compile(template);
                return template;
            }.property('object', 'meta')
        });

        //TODO: Кнопка ни как не связана с формой- можно вынести в отдельный компонент
        UMI.SaveButtonView = Ember.View.extend({
            tagName: 'button',
            classNameBindings: ['model.isDirty::disabled', 'model.isValid::disabled'],
            click: function(event){
                if(this.get('model.isDirty') && this.get('model.isValid')){
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
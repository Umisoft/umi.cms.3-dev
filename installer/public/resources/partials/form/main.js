define(
    [
        'App',
        'text!./form.hbs',
        './mixins',

        './elements/wysiwyg/main',
        './elements/select/main',
        './elements/multi-select/main',
        './elements/magellan/main',

        /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
        './elements/checkbox/main',
        './elements/radio/main',
        './elements/text/main',
        './elements/number/main',
        './elements/email/main',
        './elements/password/main',
        './elements/time/main',
        './elements/date/main',
        './elements/datetime/main',
        './elements/file/main',
        './elements/image/main',
        './elements/textarea/main',
        './formTypes/basicForm/main',
        './toolbar/main'
    ],
    function(
        UMI,
        formTpl,
        mixin,

        wysiwygElement,
        selectElement,
        multiSelectElement,
        magellanElement,

        /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
        checkboxElement,
        radioElement,
        textElement,
        numberElement,
        emailElement,
        passwordElement,
        timeElement,
        dateElement,
        datetimeElement,
        fileElement,
        imageElement,
        textareaElement,
        basicForm,
        toolbar
        ){
        'use strict';

        Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

        mixin();

        wysiwygElement();
        selectElement();
        multiSelectElement();
        magellanElement();

        /* TODO плавно переносим элементы на другую структуру + стандартизированные названия */
        checkboxElement();
        radioElement();
        textElement();
        numberElement();
        emailElement();
        passwordElement();
        timeElement();
        dateElement();
        datetimeElement();
        fileElement();
        imageElement();
        textareaElement();
        toolbar();

        UMI.FormControlController = Ember.ObjectController.extend({
            needs: ['component'],

            settings: function(){
                var settings = {};
                settings = this.get('controllers.component.settings');
                return settings;
            }.property(),

            toolbar: function(){
                var actionName = this.get('container').lookup('route:action').get('context.action.name');
                var editForm = this.get('controllers.component.contentControls').findBy('name', actionName);
                return editForm && editForm.toolbar;
            }.property('controllers.component.contentControls'),

            hasToolbar: function(){
                var toolbar = this.get('toolbar');
                return toolbar && toolbar.length;
            }.property('toolbar'),

            hasFieldset: function(){
                var hasFieldset;
                try{
                    hasFieldset = this.get('content.viewSettings.elements').isAny('type', 'fieldset');
                } catch(error){
                    var errorObject = {
                        'statusText': error.name,
                        'message': error.message,
                        'stack': error.stack
                    };
                    this.send('templateLogs', errorObject, 'component');
                } finally{
                    return hasFieldset;
                }
            }.property('model.@each')
        });

        UMI.FormControlView = Ember.View.extend({
            tagName: 'form',
            templateName: 'formControl',
            classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],
            submit: function(){
                return false;
            },
            elementView: Ember.View.extend({
                isFieldset: function(){
                    return this.get('content.type') === 'fieldset';
                }.property(),
                isExpanded: true,
                actions: {
                    expand: function(){
                        this.toggleProperty('isExpanded');
                    }
                }
            })
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

            layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{meta.label}}</span></div>{{yield}}'),

            template: function(){
                var meta = this.get('meta');
                var template;

                switch(meta.type){
                    case 'wysiwyg':         template = '{{html-editor           object=object property="' + meta.dataSource + '" validator="collection" dataSource=meta.dataSource}}'; break;
                    case 'select':          template = '{{view "select"         object=object meta=meta}}'; break;
                    case 'multi-select':    template = '{{view "multiSelect"    object=object meta=meta}}'; break;

                    case 'text':            template = '{{input              value=object.' + meta.dataSource + ' meta=meta}}'; break;
                    case 'email':           template = '{{email-element         object=object meta=meta}}'; break;
                    case 'password':        template = '{{password-element      object=object meta=meta}}'; break;
                    case 'checkbox':        template = '{{checkbox-element      object=object meta=meta}}'; break;
                    case 'multi-checkbox':  template = '{{multi-checkbox-element object=object meta=meta}}'; break; //Второй вариант отображения multi-select
                    case 'radio':           template = '{{radio-element         object=object meta=meta}}'; break;
                    case 'time':            template = '{{time-element          object=object meta=meta}}'; break;
                    case 'date':            template = '{{date-element          object=object meta=meta}}'; break;
                    case 'file':            template = '{{file-element          object=object meta=meta}}'; break;
                    case 'image':           template = '{{image-element         object=object meta=meta}}'; break;
                    case 'number':          template = '{{number-element        object=object meta=meta}}'; break;
                    case 'textarea':        template = '{{textarea-element      object=object meta=meta}}'; break;

                    default:                template = '<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>'; break;
                }

                template += '{{#if object.validErrors.' + meta.dataSource + '}}' + '<small class="error">' + '{{#each error in object.validErrors.' + meta.dataSource + '}}' + '{{error.message}}' + '{{/each}}' + '</small>' + '{{/if}}';
                template = Ember.Handlebars.compile(template);
                return template;
            }.property('object', 'meta')
        });

        //TODO: Кнопка ни как не связана с формой- можно вынести в отдельный компонент
        UMI.SaveButtonView = Ember.View.extend({
            tagName: 'a',
            classNameBindings: ['model.isDirty::umi-disabled', 'model.isValid::umi-disabled'],

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

        UMI.FormControlDropUpView = Ember.View.extend({
            classNames: ['dropdown', 'coupled'],
            classNameBindings: ['isOpen:open'],
            isOpen: false,
            iScroll: null,
            actions: {
                open: function(){
                    var self = this;
                    var el = this.$();
                    this.toggleProperty('isOpen');
                    if(this.get('isOpen')){
                        setTimeout(function(){
                            $('body').on('click.umi.form.controlDropUp', function(event){
                                var targetElement = $(event.target).closest('.umi-dropup');
                                if(!targetElement.length || targetElement[0].parentNode.getAttribute('id') !== el[0].getAttribute('id')){
                                    $('body').off('.umi.form.controlDropUp');
                                    self.set('isOpen', false);
                                }
                            });
                            if(self.get('iScroll')){
                                self.get('iScroll').refresh();
                            }
                        }, 0);
                    }
                }
            },
            didInsertElement: function(){
                var el = this.$();
                var scroll;
                var scrollElement = el.find('.s-scroll-wrap');
                if(scrollElement.length){
                    scroll = new IScroll(scrollElement[0], UMI.config.iScroll);
                }
                this.set('iScroll', scroll);
            }
        });

        basicForm();
    }
);
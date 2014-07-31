define(
    [
        'App',
        'partials/forms/partials/magellan/main',
        'partials/forms/partials/submitToolbar/main'
    ],
    function(UMI, magellan, submitToolbar){
        'use strict';

        /**
         * Базовый тип формы.
         * @example
         * Объявление формы:
         *  {{render 'formBase' model}}
         */
        return function(){

            magellan();
            submitToolbar();

            UMI.FormControllerMixin = Ember.Mixin.create(UMI.i18nInterface, {
                dictionaryNamespace: 'form',
                localDictionary: function(){
                    var form = this.get('control') || {};
                    return form.i18n;
                }.property()
            });

            UMI.FormViewMixin = Ember.Mixin.create({
                /**
                 * Тип DOM элемента
                 * @property tagName
                 * @type String
                 * @default "form"
                 */
                tagName: 'form',
                submit: function(){
                    return false;
                },
                /**
                 * Проверяет наличие fieldset
                 * @method hasFieldset
                 * @return bool
                 */
                hasFieldset: function(){
                    return this.get('context.control.meta.elements').isAny('type', 'fieldset');
                }.property('context.control.meta'),

                elementView: Ember.View.extend({
                    classNameBindings: ['isField'],
                    isFieldset: function(){
                        return this.get('content.type') === 'fieldset';
                    }.property(),
                    isExpanded: true,
                    isField: function(){
                        if(!this.get('isFieldset')){
                            return this.gridType();
                        }
                    }.property(),
                    /**
                     * @method gridType
                     */
                    gridType: function(){
                        var wideElements = ['wysiwyg', 'permissions'];
                        var widthClass = 'large-4 small-12';
                        if(wideElements.contains(this.get('content.type'))){
                            widthClass = 'small-12';
                        }
                        return 'umi-columns ' + widthClass;
                    },

                    actions: {
                        expand: function(){
                            this.toggleProperty('isExpanded');
                        }
                    }
                })
            });

            UMI.FieldMixin = Ember.Mixin.create({
                isRequired: function(){
                    var validators = this.get('meta.validators');
                    if(Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')){
                        return ' *';
                    }
                }.property(),

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span></div>{{yield}}'),

                template: function(){
                    var meta;
                    var template;
                    try{
                        meta = this.get('meta');
                        template = this.get(Ember.String.camelize(meta.type) + 'Template') || '';
                        if(!template){
                            throw new Error('Для поля с типом ' + meta.type + ' не реализован шаблонный метод.');
                        }
                    } catch(error){
                        this.get('controller').send('backgroundError', error);// TODO: при первой загрузке сообщения не всплывают.
                    } finally{
                        template = this.extendTemplate(template);
                        return Ember.Handlebars.compile(template);
                    }
                }.property(),

                /**
                 * Метод используется декораторами для расширения базового шаблона.
                 * @method extendTemplate
                 * @param template
                 * @returns String
                 */
                extendTemplate: function(template){
                    return template;
                },

                textTemplate: function(){
                    return '{{view "textElement" object=view.object meta=view.meta}}';
                }.property(),

                emailTemplate: function(){
                    return '{{view "emailElement" object=view.object meta=view.meta}}';
                }.property(),

                passwordTemplate: function(){
                    return '{{view "passwordElement" object=view.object meta=view.meta}}';
                }.property(),

                numberTemplate: function(){
                    return '{{view "numberElement" object=view.object meta=view.meta}}';
                }.property(),

                colorTemplate: function(){
                    return '{{view "colorElement" object=view.object meta=view.meta}}';
                }.property(),

                timeTemplate: function(){
                    return '{{time-element object=view.object meta=view.meta}}';
                }.property(),

                dateTemplate: function(){
                    return '{{view "dateElement" object=view.object meta=view.meta}}';
                }.property(),

                datetimeTemplate: function(){
                    return '{{view "dateTimeElement" object=view.object meta=view.meta}}';
                }.property(),

                fileTemplate: function(){
                    return '{{view "fileElement" object=view.object meta=view.meta}}';
                }.property(),

                imageTemplate: function(){
                    return '{{view "imageElement" object=view.object meta=view.meta}}';
                }.property(),

                textareaTemplate: function(){
                    return '{{view "textareaElement" object=view.object meta=view.meta}}';
                }.property(),

                wysiwygTemplate: function(){
                    return '{{view "htmlEditor" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "select" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                multiSelectTemplate: function(){
                    return '{{view "multiSelect" object=view.object meta=view.meta name=view.meta.attributes.name}}';
                }.property(),

                checkboxTemplate: function(){
                    return '{{view "checkboxElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function(){
                    return '{{view "checkboxGroupElement" object=view.object meta=view.meta}}';
                }.property(),

                radioTemplate: function(){
                    return '{{view "radioElement" object=view.object meta=view.meta}}';
                }.property()
            });

            UMI.FormBaseController = Ember.ObjectController.extend(UMI.FormControllerMixin, {});

            UMI.FormBaseView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/form',

                /**
                 * Классы view
                 * @property classNames
                 * @type Array
                 */
                classNames: ['s-margin-clear', 's-full-height', 'umi-form-control', 'umi-validator'],

                attributeBindings: ['action'],

                action: function(){
                    return this.get('context.control.meta.attributes.action');
                }.property('context.control.meta.attributes.action'),

                actions: {
                    submit: function(handler){
                        var self = this;
                        if(handler){
                            handler.addClass('loading');
                        }
                        var data = this.$().serialize();
                        $.ajax({
                            type: "POST",
                            url: self.get('action'),
                            global: false,
                            data: data,
                            success: function(results){
                                var meta = Ember.get(results, 'result.save');
                                var context = self.get('context');
                                Ember.set(context, 'control.meta', meta);
                                handler.removeClass('loading');
                                var params = {type: 'success', 'content': 'Сохранено.', duration: false};
                                UMI.notification.create(params);
                            },
                            error: function(results){
                                var meta = Ember.get(results, 'responseJSON.result.save');
                                var context = self.get('context');

                                Ember.set(context, 'control.meta', meta);
                                handler.removeClass('loading');
                            }
                        });
                    }
                },

                submitToolbarView: UMI.SubmitToolbarView.extend({
                    elementView: Ember.View.extend(UMI.ToolbarElement, {
                        buttonView: function(){
                            var params = {};
                            if(this.get('context.behaviour.name') === 'save'){
                                params = {
                                    actions: {
                                        save: function(){
                                            this.get('parentView.parentView.parentView').send('submit', this.$());
                                        }
                                    }
                                };
                            }
                            return UMI.ButtonView.extend(params);
                        }.property()
                    })
                })
            });

            UMI.FieldBaseView = Ember.View.extend(UMI.FieldMixin, {
                layout: function(){
                    var type = this.get('meta.type');
                    var layout = '<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span></div>{{yield}}';

                    switch(type){
                        case 'checkbox':
                            layout = '{{yield}}{{view.isRequired}}';
                    }

                    var validate = '{{#if view.validateErrors}}<small class="error">{{view.validateErrors}}</small>{{/if}}';
                    layout = layout + validate;
                    return Ember.Handlebars.compile(layout);
                }.property(),
                classNameBindings: ['validateErrors:error'],
                validateErrors: function(){
                    var errors = this.get('meta.errors');
                    if(Ember.typeOf(errors) === 'array'){
                        return errors.join('.');
                    }
                }.property('meta.errors.@each'),
                singleCollectionObjectRelationTemplate: function(){
                    return '{{view "singleCollectionObjectRelationElement" object=view.object meta=view.meta}}';
                }.property()
            });
        };
    }
);
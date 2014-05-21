define(
    [
        'App',
        'text!./form.hbs',
        'partials/forms/elements/main',
        'partials/forms/partials/magellan/main',
        'partials/forms/partials/toolbar/main'
    ],
    function(UMI, formTpl, elements, magellan, toolbar){
        'use strict';

        /**
         * Базовый тип формы.
         * @example
         * Объявление формы:
         *  {{render 'formBase' model}}
         */
        return function(){

            elements();
            magellan();
            toolbar();

            UMI.FormBaseController = Ember.ObjectController.extend({
                /**
                 * Toolbar кнопок для формы
                 * @method toolbar
                 */
                toolbar: function(){}.property(),
                /**
                 * Проверяет наличие toolbar
                 * @method hasToolbar
                 * @return bool
                 */
                hasToolbar: function(){
                    var toolbar = this.get('toolbar');
                    return toolbar && toolbar.length;
                }.property('toolbar'),
                /**
                 * Проверяет наличие fieldset
                 * @method hasFieldset
                 * @return bool
                 */
                hasFieldset: function(){
                    return this.get('model.elements').isAny('type', 'fieldset');
                }.property('model')
            });

            UMI.FormBaseView = Ember.View.extend({
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layout: Ember.Handlebars.compile(formTpl),
                /**
                 * Тип DOM элемента
                 * @property tagName
                 * @type String
                 * @default "form"
                 */
                tagName: 'form',
                /**
                 * Классы view
                 * @property classNames
                 * @type Array
                 */
                classNames: ['s-margin-clear', 's-full-height', 'umi-form-control'],

                attributeBindings: ['action'],

                action: function(){
                    return this.get('context.model.attributes.action');
                }.property(''),

                loading: false,

                submit: function(event){
                    event.preventDefault();
                    var self = this;
                    self.toggleProperty('loading');
                    var data = this.$().serialize();
                    $.post(self.get('action'), data).then(function(result){
                        self.toggleProperty('loading');
                    });
                },

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
                        return 'umi-columns ' + (this.get('content.type') === 'wysiwyg' ? 'small-12' : 'large-4 small-12');
                    },

                    actions: {
                        expand: function(){
                            this.toggleProperty('isExpanded');
                        }
                    }
                })
            });


            UMI.FieldBaseView = Ember.View.extend({
                /**
                 * Метаданные свойства. В базовой реализации
                 * соответствует самому объекту
                 * @property metaBinding
                 * @type String
                 */
                metaBinding: 'object',

                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{meta.label}}</span></div>{{yield}}'),

                template: function(){
                    var meta = this.get('meta');
                    var templateMethod = this[Ember.String.camelize(meta.type) + 'Template'];

                    if(Ember.typeOf(templateMethod) === 'function'){
                        return Ember.Handlebars.compile(templateMethod(this));
                    } else{
                        Ember.assert('Для поля с типом ' + meta.type + ' не реализован шаблонный метод.');
                        // TODO: В случае отсутствия шаблона нужно выводить ошибку.
                    }
                }.property('meta'),

                textTemplate: function(){
                    return '{{input value=object.value meta=view.meta}}';
                },

                emailTemplate: function(){
                    return '{{email-element object=object meta=view.meta}}';
                },

                passwordTemplate: function(){
                    return '{{password-element object=object meta=view.meta}}';
                },

                numberTemplate: function(){
                    return '{{number-element object=object meta=view.meta}}';
                },

                wysiwygTemplate: function(){
                    return '{{html-editor object=object property="' + this.get('meta').dataSource + '" validator="collection" dataSource=view.meta.dataSource}}';
                },

                selectTemplate: function(){
                    return '{{view "select" object=object meta=view.meta}}';
                },

                multiSelectTemplate: function(){
                    return '{{view "multiSelect" object=object meta=view.meta}}';
                },

                checkboxTemplate: function(){
                    return '{{checkbox-element object=object meta=view.meta}}';
                },

                multiCheckboxTemplate: function(){
                    return '{{multi-checkbox-element object=object meta=view.meta}}';
                },

                radioTemplate: function(){
                    return '{{radio-element object=object meta=view.meta}}';
                },

                timeTemplate: function(){
                    return '{{time-element object=object meta=view.meta}}';
                },

                dateTemplate: function(){
                    return '{{date-element object=object meta=view.meta}}';
                },

                fileTemplate: function(){
                    return '{{file-element object=object meta=view.meta}}';
                },

                imageTemplate: function(){
                    return '{{image-element object=object meta=view.meta}}';
                },

                textareaTemplate: function(){
                    return '{{textarea-element object=object meta=view.meta}}';
                }
            });
        };
    }
);
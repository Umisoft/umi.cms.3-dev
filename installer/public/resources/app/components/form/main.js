define(
    [
        'App',
        'text!./form.hbs',
        'app/components/form/elements/select/main',
        'app/components/form/elements/multiSelect/main',
        'app/components/form/elements/datepicker/main',
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
        classNames: ['s-margin-clear', 's-full-height'],
        classNameBindings: ['class:data.class'],
        attributeBindings: ['abide:data-abide'],
        abide: 'ajax'
    });

    UMI.FieldView = Ember.View.extend({
        classNames: ['umi-columns'],
        classNameBindings: ['wide'],
        wide: function(){
            return this.get('meta.type') === 'wysiwyg' ? 'small-12' : 'large-4 small-12';
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
                case 'number': // TODO: Поле типа "number" в firefox не работает
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
                case 'datetime':
                    template = Ember.Handlebars.compile('{{date-picker object=object property="' + meta.dataSource + '"}}');
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

    UMI.MagellanView = Ember.View.extend({
        classNames: ['magellan-menu', 's-full-height-before'],
        focusName: null,
        buttonView: Ember.View.extend({
            tagName: 'a',
            classNameBindings: ['isFocus:focus'],
            isFocus: function(){
                return this.get('model.name') === this.get('parentView.focusName');
            }.property('parentView.focusName'),
            click: function(){
                var delta = 5;
                var fieldset = document.getElementById('fieldset-' + this.get('model.name'));
                $(fieldset).closest('.maggelan-content').animate({'scrollTop': fieldset.offsetTop - delta}, 0);
            }
        }),
        init: function(){
            var elements = this.get('elements');
            elements = elements.filter(function(item){
                return item.type === 'fieldset';
            });
            this.set('focusName', elements.get('firstObject.name'));
        },
        didInsertElement: function(){
            var self = this;
            var scrollArea = $('.magellan-menu').parent().find('.maggelan-content');//TODO: По хорошему нужно выбирать элемент через this.$()
            if(!scrollArea.length){
                return;
            }
            scrollArea.on('scroll.umi.magellan', function(){
                var delta = 5;
                var scrollOffset = $(this).scrollTop();
                var focusField;
                var fieldset = $(this).children('fieldset');
                var scrollElement;
                for(var i = 0; i < fieldset.length; i++){
                    scrollElement = fieldset[i].offsetTop;
                    if(scrollElement - delta <= scrollOffset && scrollOffset <= scrollElement + fieldset[i].offsetHeight){
                        focusField = fieldset[i];
                    }
                }
                if(focusField){
                    self.set('focusName', focusField.id.replace(/^fieldset-/g, ''));
                }
            });
        }
    });
});
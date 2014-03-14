define(['App', 'text!./form.hbs'], function(UMI, formTpl){
    'use strict';
    Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

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
            return this.get('meta.type') === 'textarea' ? 'small-12' : 'large-4 medium-12';
        }.property('meta.type'),
        layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{title}}</label></div>{{yield}}'),
        template: function(){
            var meta = this.get('meta');
            var template;

            switch(meta.type){
                case 'text':
                    template = Ember.Handlebars.compile('{{input type="text" value=object.' + meta.name + ' placeholder=placeholder}}');
                    break;
                /*case 'textarea':
                    template = Ember.Handlebars.compile('{{textarea value=object.' + meta.name + ' placeholder=meta.placeholder}}');
                    break;*/
                case 'textarea': case 'html':
                    template = Ember.Handlebars.compile('{{html-editor object=object property="' + meta.name + '"}}');
                    break;
                case 'datetime':
                    template = Ember.Handlebars.compile('{{date-picker object=object property="' + meta.name + '"}}');
                    break;
                case 'number':
                    template = Ember.Handlebars.compile('{{input type="number" value=object.' + meta.name + '}}');
                    break;
                case 'checkbox':
                    template = Ember.Handlebars.compile('{{input type="checkbox" checked=object.' + meta.name + ' name=name}}<label for="' + meta.name + '"></label>');
                    break;
                case 'choice':
                    template = Ember.Handlebars.compile('{{view Ember.Select name=' + meta.name + ' content=object.' + meta.name + ' optionValuePath="content.id" optionLabelPath="content.displayName" prompt=placeholder}}');
                    break;
                case 'file':
                    template = Ember.Handlebars.compile('<div class="umi-input-wrapper-file">{{input type="file" class="umi-file" value=object.' + meta.name + '}}<i class="icon icon-cloud"></i></div>');
                    break;
                default:
                    template = Ember.Handlebars.compile('<div>Для поля типа <b>' + meta.type + '</b> не предусмотрен шаблон.</div>');
                    break;
            }
            return template;
        }.property('object', 'meta')
    });


    UMI.HtmlEditorComponent = Ember.Component.extend({
        tagName: 'div',
        object: null,
        property: null,
        valueObject: function(){
            return this.get('object.' + this.get("property"));
        }.property('object', 'property'),
        classNames: ['ckeditor-row'],
        layout: Ember.Handlebars.compile('{{textarea value=valueObject placeholder=meta.placeholder}}'),
        didInsertElement: function(){
            var self = this;
            var el = this.$().children('textarea');
            var edit = CKEDITOR.replace(el[0].id);
            var updateContent = function(event){
                if(event.editor.checkDirty()){
                    self.get('object').set(self.get('property'), edit.getData());
                }
            };
            edit.on('blur', function(event){
                updateContent(event);
            });
            edit.on('key', function(event){// TODO: Это событие было добавлено только из-за того, что событие save срабатывает быстрее blur. Кажется можно сделать лучше.
                updateContent(event);
            });
        }
    });

    UMI.DatePickerComponent = Ember.Component.extend({
        tagName: 'div',
        classNames: ['umi-input-wrapper-date'],
        object: null,
        property: null,
        valueObject: function(){
            return this.get('object.' + this.get("property") + '.date');
        }.property('object', 'property'),
        changeValueObject: function(){
            var property = this.get('object.' + this.get("property"));
            property.date = this.get('valueObject');
            this.get('object').set(this.get('property'), property);
        }.observes('valueObject'),
        layout: Ember.Handlebars.compile('{{input type="text" class="umi-date" value=valueObject}}<i class="icon icon-calendar"></i>'),
        didInsertElement: function(){
            var self = this;
            var el = this.$().children('.umi-date');
            el.jdPicker({date_format: "dd/mm/YYYY"});
        }
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
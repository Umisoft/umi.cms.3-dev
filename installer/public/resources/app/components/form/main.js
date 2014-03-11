define(['App', 'text!./form.hbs'], function(UMI, formTpl){
    'use strict';
    Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

    UMI.FormControlView = Ember.View.extend({
        tagName: 'form',
        templateName: 'formControl',
        classNames: ['s-full-height'],
        classNameBindings: ['class:data.class'],
        attributeBindings: ['abide:data-abide'],
        abide: 'ajax',
        actions: {
            save: function(object){
                 object.save();
            }
        }
    });

    /*Ember.TEMPLATES['UMI/field/string'] = Ember.Handlebars.compile('{{input type="text" va}}');
    Ember.TEMPLATES['UMI/field/text'] = Ember.Handlebars.compile(formTpl);
     Ember.TEMPLATES['UMI/field/html'] = Ember.Handlebars.compile(formTpl);
     Ember.TEMPLATES['UMI/field/data'] = Ember.Handlebars.compile(formTpl);
     Ember.TEMPLATES['UMI/field/number'] = Ember.Handlebars.compile(formTpl);*/


    UMI.FieldView = Ember.View.extend({
        didInsertElement: function(){
            //console.log('Form didInsertElement');
            //TODO Вставка элемента формы на страницу - последенее событие. Ведь так?
            //Этот код вызывается для каждой отрисованой формы(и это действительно ужасно), хотя должен только после последнего и желательно только когда есть Date
            //Вопрос: куда его можно перенести? Круто было бы к добавить соответствующему template, но не сработает.
            //jdPicker ищет элемент по классу (а как ещё?), а в другие моменты времени элемента не существует
            //Может с on пошаманить?
            //Или сделать отдельный компонент для Date и связывать с ним календарь по id?
            //Оставляйте комментарии, подписывайтесь, ставьте лайки
            /*$($.jdPicker.initialize);*/


//            Те же проблемы
           // CKEDITOR.replace('ckeditor-1'); //Здесь id передаётся

            /*$('legend').mousedown(function(){
                $(this).find('i').toggleClass("icon-top icon-bottom");
                $(this).parent().find('div').toggle();
            });*/

            /*Ember.run.scheduleOnce('afterRender', this, function(){
                $($.jdPicker.initialize);
            });*/
        },

        template: function(){
            var meta = this.get('meta');
            var template;
            switch(meta.type){
                case 'text':
                    template = Ember.Handlebars.compile('{{input type="text" value=object.' + meta.name + ' placeholder=meta.placeholder}}');
                    break;
                /*case 'textarea':
                    template = Ember.Handlebars.compile('{{textarea value=object.' + meta.name + ' placeholder=meta.placeholder}}');
                    break;*/
                case 'textarea': case 'html':
                    template = Ember.Handlebars.compile('{{html-editor object=object property="' + meta.name + '"}}');
                    break;
                case 'date':
                    template = Ember.Handlebars.compile('<div class="umi-input-wrapper-date">{{input type="text" class="umi-date" value=object.' + meta.name + '}}<i class="icon icon-calendar"></i></div>');
                    break;
                case 'number':
                    template = Ember.Handlebars.compile('{{input type="number" value=object.' + meta.name + '}}');
                    break;
                case 'checkbox':
                    template = Ember.Handlebars.compile('{{input type="checkbox" checked=object.' + meta.name + '}}');
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

    //TODO: Для форм нужно не забыть в шаблоне, и в остальных местах биндить все возможные атрибуты
});
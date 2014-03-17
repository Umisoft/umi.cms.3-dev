define(['text!./templates/formControl.hbs', 'App'], function(formControlTpl, UMI){
    'use strict';
    Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formControlTpl);

    UMI.FormControlView = Ember.View.extend({
        tagName: 'form',
        templateName: 'formControl',
        classNames: ['s-full-height'],
        classNameBindings: ['class:data.class'],
        attributeBindings: ['abide:data-abide'],
        abide: 'ajax',
        actions: {
            save: function(object){
                object.then(function(object){
                    object.save();
                });
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
            //TODO Вставка элемента формы на страницу - последенее событие. Ведь так?
            //Этот код вызывается для каждой отрисованой формы(и это действительно ужасно), хотя должен только после последнего и желательно только когда есть Date
            //Вопрос: куда его можно перенести? Круто было бы к добавить соответствующему template, но не сработает.
            //jdPicker ищет элемент по классу (а как ещё?), а в другие моменты времени элемента не существует
            //Может с on пошаманить?
            //Или сделать отдельный компонент для Date и связывать с ним календарь по id?
            //Оставляйте комментарии, подписывайтесь, ставьте лайки
            $($.jdPicker.initialize);


//            Те же проблемы
            CKEDITOR.replace('ckeditor-1'); //Здесь id передаётся

            $('legend').mousedown(function(){
                $(this).find('i').toggleClass("icon-top icon-bottom");
                $(this).parent().find('div').toggle();
            });

            Ember.run.scheduleOnce('afterRender', this, function(){
                $($.jdPicker.initialize);
            });
        },

        template: function(){
            var meta = this.get('meta');
            var template;
            switch(meta.type){
                case 'date':
                    template = Ember.Handlebars.compile('{{input type="text" value=object.' + meta.name + ' placeholder=meta.placeholder}}');
                    break;
                case 'textarea':
                    template = Ember.Handlebars.compile('{{textarea value=object.' + meta.name + ' placeholder=meta.placeholder}}');
                    break;
                case 'html':
                    //TODO Захардкожен id wisiwig
                    template = Ember.Handlebars.compile('{{textarea data-type="ckeditor" id="ckeditor-1" value=object.' + meta.name + '}}');
                    break;
                case 'text':
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


    // CkEditor
    UMI.CkEditorComponent = Ember.Component.extend({
        tagName: 'div',
        classNames: ['ckeditor-row'],// TODO: Атрибут required не биндится к хелперу textarea
        template: Ember.Handlebars.compile('{{textarea value=value required=attributes.required pattern=pattern class=attributes.class disabled=disabled}}'),
        didInsertElement: function(){
            var el = this.$().children('textarea');
            Ember.run.next(this, function(){
                CKEDITOR.replace(el[0].id);
            });
        }
    });
    //TODO: Для форм нужно не забыть в шаблоне, и в остальных местах биндить все возможные атрибуты
});
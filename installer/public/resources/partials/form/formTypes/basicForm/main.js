define([
    'App',
    'text!./form.hbs'
    ],
    function(UMI, formTpl){
        'use strict';
        return function(){
            UMI.FormBasicController = Ember.View.extend({
                hasFieldset: function(){
                    return this.get('model.elements').isAny('type', 'fieldset');
                }.property('model')
            });

            UMI.FormBasicView = Ember.View.extend({
                tagName: 'form',
                classNames: ['ember-view', 's-margin-clear', 's-full-height', 'umi-form-control'],
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
                layout: Ember.Handlebars.compile(formTpl)
            });


            UMI.BasicFieldView = Ember.View.extend({
                classNames: ['umi-columns'],
                classNameBindings: ['wide'],
                wide: function(){
                    return this.get('object.type') === 'wysiwyg' ? 'small-12' : 'large-4 small-12';
                }.property('object.type'),
                //TODO Вёрстка косячная. Здесь span или label?
                layout: Ember.Handlebars.compile('<div><span class="umi-form-label">{{object.label}}</label></div>{{yield}}'),
                template: function(){
                    var object = this.get('object');
                    var template;
                    switch(object.type){
                        case 'text':
                            template = '{{input type="text" name=object.name value=object.value placeholder=placeholder}}';
                            break;
                        case 'textarea':
                            template = '{{textarea name=object.name value=object.value placeholder=meta.placeholder}}';
                            break;
                        case 'wysiwyg':
                            template = '{{html-editor name=object.name object=object property="value"}}';
                            break;
                        case 'checkbox':        template = '<label>{{view Ember.Checkbox checked=object.value}} {{object.label}}</label>'; break;
                        default:
                            template = '<div>Для поля типа <b>' + object.type + '</b> не предусмотрен шаблон.</div>';
                            break;
                    }
                    template = Ember.Handlebars.compile(template);
                    return template;
                }.property('object')
            });
        };
    }
);
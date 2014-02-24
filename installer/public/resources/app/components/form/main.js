define(['App', 'text!./form.hbs'],
	function(UMI, formTpl){
		'use strict';
		Ember.TEMPLATES['UMI/formControl'] = Ember.Handlebars.compile(formTpl);

		// для хелперов инпутов не работает атрибут required. Исправим это ( костыль? )
		Ember.TextField.reopen({
			attributeBindings: ['required', 'pattern']
		});

		Ember.TextArea.reopen({
			attributeBindings: ['required', 'pattern']
		});

		UMI.TableControlComponent = Ember.Component.extend({
			classNames: ['umi-table-control']
		});


		UMI.FormControlView = Ember.View.extend({
			tagName: 'form',
			templateName: 'formControl',
			classNames: ['s-full-height'],
			classNameBindings: ['class:data.class'],
			attributeBindings: ['abide:data-abide'],
			abide: 'ajax',
			actions: {
				save: function(object){
					//console.log(object.get('title'));
					object.save();
				},
				delete: function(object){
					object.deleteRecord();
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
			template: function(){
				var meta = this.get('meta');
				var template;
				switch(meta.type){
					case 'string':
						template = Ember.Handlebars.compile('{{input type="text" value=object.' + meta.name + '}}');
						break;
					case 'text':
						template = Ember.Handlebars.compile('{{textarea value=object.' + meta.name + '}}');
						break;
					case 'html':
						template = Ember.Handlebars.compile('{{textarea data-type="ckeditor" value=object.' + meta.name + '}}');
						break;
					case 'date':
						template = Ember.Handlebars.compile('{{input type="date" value=object.' + meta.name + '}}');
						break;
					case 'number':
						template = Ember.Handlebars.compile('{{input type="number" value=object.' + meta.name + '}}');
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
	}
);
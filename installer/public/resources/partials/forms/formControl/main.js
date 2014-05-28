define(
    ['App', 'text!./form.hbs'],

    function(UMI, formTpl){
        "use strict";

        return function(){
            UMI.FormControlController = UMI.FormBaseController.extend({
                needs: ['component'],

                settings: function(){
                    var settings = {};
                    settings = this.get('controllers.component.settings');
                    return settings;
                }.property(),

                toolbar: function(){
                    var actionName = this.get('container').lookup('route:action').get('context.action.name');
                    var editForm = this.get('controllers.component.contentControls').findBy('name', actionName);
                    return [
                        {"elementType": "dropdownButton", "displayName": "Создать", "elements": [
                            {"type":"create", "displayName":"Создать Рубрика новостей","typeName":"base"}
                        ]},
                        {"elementType": "button", "type":"backToList", "displayName": "Вернуться к списку"},
                        {"elementType": "button", "type":"switchActivity", "displayName": "Сменить активность"},
                        {"elementType": "button", "type":"viewOnSite", "displayName": "Открыть страницу в новом окне"},
                        {"elementType": "button", "type":"backupList","displayName": "Предыдущие версии"},
                        {"elementType": "button", "type":"trash", "displayName": "Удалить в корзину"},
                        {"elementType": "button", "type":"delete","displayName": "Удалить навсегда"}
                    ];//editForm && editForm.toolbar;
                }.property('controllers.component.contentControls'),

                contextMenu: function(){
                    var actionName = this.get('container').lookup('route:action').get('context.action.name');
                    var editForm = this.get('controllers.component.contentControls').findBy('name', actionName);
                    return [{"displayName": "Сохранить", "type": "apply"}];//editForm
                }.property('controllers.component.contentControls'),

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

            UMI.FormControlView = UMI.FormBaseView.extend({
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layout: Ember.Handlebars.compile(formTpl),

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                submit: function(){
                    return false;
                }
            });

            UMI.FieldFormControlView = UMI.FieldBaseView.extend({
                classNameBindings: ['isError:error'],

                isError: function(){
                    var meta = this.get('meta');
                    return !!this.get('object.validErrors.' + meta.dataSource);
                }.property('object.validErrors'),

                extendTemplate: function(template){
                    var meta = this.get('meta');
                    return template + '{{#if object.validErrors.' + meta.dataSource + '}}' + '<small class="error">' + '{{#each error in object.validErrors.' + meta.dataSource + '}}' + '{{error.message}}' + '{{/each}}' + '</small>' + '{{/if}}';
                },

                textTemplate: function(){
                    return '{{text-element object=object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "selectCollection" object=object meta=view.meta}}';
                }.property()
            });
        };
    }
);
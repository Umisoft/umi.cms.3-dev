define(
    ['App', 'text!./form.hbs', 'partials/forms/partials/siblingsNavigation/main'],

    function(UMI, formTpl, siblingsNavigation){
        "use strict";

        return function(){
            siblingsNavigation();

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
                        {"type": "dropDownButton", "displayName": "Создать", "list": [
                            {"behavior":"create", "displayName":"Создать Рубрика новостей","typeName":"base"}
                        ]},
                        {"type": "button", "behavior":"backToList", "displayName": "Вернуться к списку"},
                        {"type": "buttonSwitchActivity", "behavior":"switchActivity", "displayName": "Сменить активность"},
                        {"type": "button", "behavior":"viewOnSite", "displayName": "Открыть страницу в новом окне"},
                        {"type": "buttonBackupList", "behavior":"backupList","displayName": "Предыдущие версии"},
                        {"type": "button", "behavior":"trash", "displayName": "Удалить в корзину"},
                        {"type": "button", "behavior":"delete","displayName": "Удалить навсегда"}
                    ];//editForm && editForm.toolbar;
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
define(
    ['App', 'text!./form.hbs', './toolbar/main'],

    function(UMI, formTpl, toolbar){
        "use strict";

        return function(){
            toolbar();

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
                    return editForm && editForm.toolbar;
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
                },

                elementView: Ember.View.extend({
                    isFieldset: function(){
                        return this.get('content.type') === 'fieldset';
                    }.property(),
                    isExpanded: true,
                    actions: {
                        expand: function(){
                            this.toggleProperty('isExpanded');
                        }
                    }
                })
            });

            UMI.FieldView = Ember.View.extend({
                classNameBindings: ['wide', 'isError:error'],

                isError: function(){
                    var meta = this.get('meta');
                    return !!this.get('object.validErrors.' + meta.dataSource);
                }.property('object.validErrors'),

                textTemplate: function(self){
                    return '{{input value=object.' + self.get('meta').dataSource + ' meta=view.meta}}';
                },

                template: function(){
                    var meta = this.get('meta');
                    var template;
                    template += '{{#if object.validErrors.' + meta.dataSource + '}}' + '<small class="error">' + '{{#each error in object.validErrors.' + meta.dataSource + '}}' + '{{error.message}}' + '{{/each}}' + '</small>' + '{{/if}}';
                    template = Ember.Handlebars.compile(template);
                    return template;
                }.property('object', 'meta')
            });
        };
    }
);
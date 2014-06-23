define(
    ['App', 'text!./form.hbs', 'partials/forms/partials/siblingsNavigation/main'],

    function(UMI, formTpl, siblingsNavigation){
        "use strict";

        return function(){
            siblingsNavigation();

            UMI.FormControlController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
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
                }.property('object'),

                submitToolbar: function(){
                    var actionName = this.get('container').lookup('route:action').get('context.action.name');
                    var editForm = this.get('controllers.component.contentControls').findBy('name', actionName);
                    return editForm && editForm.submitToolbar;
                }.property('object'),

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

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layout: Ember.Handlebars.compile(formTpl),

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control']
            });

            UMI.FieldFormControlView = Ember.View.extend(UMI.FieldMixin, {
                classNameBindings: ['isError:error'],

                isError: function(){
                    var meta = this.get('meta');
                    return !!this.get('object.validErrors.' + meta.dataSource);
                }.property('object.validErrors'),

                wysiwygTemplate: function(){
                    return '{{view "htmlEditorCollection" object=object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "selectCollection" object=object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function(){
                    return '{{view "checkboxGroupCollectionElement" object=object meta=view.meta}}';
                }.property()
            });
        };
    }
);
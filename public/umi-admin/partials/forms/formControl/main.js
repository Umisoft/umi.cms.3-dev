define(['App'],

    function(UMI){
        "use strict";

        return function(){

            UMI.FormControlController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                needs: ['component'],

                settings: function(){
                    var settings = {};
                    settings = this.get('controllers.component.settings');
                    return settings;
                }.property()
            });

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control']
            });

            UMI.FieldFormControlView = Ember.View.extend(UMI.FieldMixin, {
                isRequired: function(){
                    var dataSource = this.get('meta.dataSource');
                    var validators = this.get('object').validatorsForProperty(dataSource);
                    if(Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')){
                        return ' *';
                    }
                }.property(),
                classNameBindings: ['isError:error'],

                isError: function(){
                    var dataSource = this.get('meta.dataSource');
                    return !!this.get('object.validErrors.' + dataSource);
                }.property('object.validErrors'),

                wysiwygTemplate: function(){
                    return '{{view "htmlEditorCollection" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function(){
                    return '{{view "selectCollection" object=view.object meta=view.meta}}';
                }.property(),

                checkboxTemplate: function(){
                    return '{{view "checkboxCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function(){
                    return '{{view "checkboxGroupCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                permissionsTemplate: function(){
                    return '{{view "permissions" object=object meta=view.meta}}';
                }.property()
            });
        };
    }
);
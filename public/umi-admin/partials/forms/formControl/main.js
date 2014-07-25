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
                }.property(),

                inputElements: function(){
                    var elements = this.get('control.meta.elements');
                    var inputElements = [];
                    var i;
                    for(i = 0; i < elements.length; i++){
                        if(Ember.get(elements[i], 'type') === 'fieldset' && Ember.typeOf(Ember.get(elements[i], 'elements')) === 'array'){
                            inputElements = inputElements.concat(elements[i].elements);
                        } else{
                            inputElements.push(elements[i]);
                        }
                    }
                    return inputElements;
                },

                validationErrors: function(){
                    var validErrors = this.get('object.validErrors');
                    var stack = [];
                    var key;
                    var inputElements = this.inputElements();
                    var validateErrorLabel = 'Объект не валиден.';
                    var settings = {
                        type: 'error',
                        duration: false,
                        title: validateErrorLabel,
                        kind: 'validate',
                        close: false
                    };

                    for(key in validErrors){
                        if(validErrors.hasOwnProperty(key) && !inputElements.findBy('dataSource', key)){
                            stack.push('<div>' + key + ': ' + validErrors[key] + '</div>');
                        }
                    }

                    if(stack.length){
                        settings.content = stack.join();
                        UMI.notification.create(settings);
                    } else{
                        UMI.notification.removeWithKind('validateError');
                    }
                }.observes('object.validErrors.@each'),

                actions: {
                    save: function(params){
                        var elements = this.inputElements();
                        elements = elements.mapBy('dataSource');
                        params.fields = elements;
                        this.get('controllers.component').send('save', params);
                    },

                    saveAndGoBack: function(params){
                        var elements = this.inputElements();
                        elements = elements.mapBy('dataSource');
                        params.fields = elements;
                        this.get('controllers.component').send('saveAndGoBack', params);
                    }
                }
            });

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                willDestroyElement: function(){
                    this.get('controller').removeObserver('object.validErrors.@each');
                }
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
                    var isValid = !!this.get('object.validErrors.' + dataSource);
                    return isValid;
                }.property('object.validErrors.@each'),

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
                    return '{{view "permissions" object=view.object meta=view.meta}}';
                }.property(),

                objectRelationTemplate: function(){
                    return '{{view "objectRelationElement" object=view.object meta=view.meta}}';
                }.property(),

                pageRelationTemplate: function(){
                    return this.get('objectRelationTemplate');
                }.property()
            });
        };
    }
);
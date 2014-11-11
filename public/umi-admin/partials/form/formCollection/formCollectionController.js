define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormCollectionController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                needs: ['component'],

                formElementsBinding: 'control.meta.elements',

                objectBinding: 'model.object',

                settings: function() {
                    return this.get('controllers.component.settings');
                }.property(),

                inputElements: function() {
                    var elements = this.get('control.meta.elements');
                    var inputElements = [];
                    var i;

                    for (i = 0; i < elements.length; i++) {
                        if (Ember.get(elements[i], 'type') === 'fieldset' &&
                            Ember.typeOf(Ember.get(elements[i], 'elements')) === 'array') {
                            inputElements = inputElements.concat(elements[i].elements);
                        } else {
                            inputElements.push(elements[i]);
                        }
                    }

                    return inputElements;
                },

                validationErrors: function() {//***sdffsdTODO: Вот это не сработает?
                    var validErrors = this.get('object.validErrors');
                    var stack = [];
                    var key;
                    var inputElements = this.inputElements();
                    var validateErrorLabel = UMI.i18n.getTranslate('Object') + ' ' +
                        UMI.i18n.getTranslate('Not valid').toLowerCase() + '.';
                    var settings = {
                        type: 'error',
                        duration: false,
                        title: validateErrorLabel,
                        kind: 'validate',
                        close: false
                    };

                    for (key in validErrors) {
                        if (validErrors.hasOwnProperty(key) && !inputElements.findBy('dataSource', key)) {
                            stack.push('<div>' + key + ': ' + validErrors[key] + '</div>');
                        }
                    }

                    if (stack.length) {
                        settings.content = stack.join();
                        UMI.notification.create(settings);
                    } else {
                        UMI.notification.removeWithKind('validateError');
                    }
                }.observes('object.validErrors.@each'),

                actionWithCustomValidate: function(actionName, params) {//WTF?
                    var isValid = this.validateForm();
                    if (isValid) {
                        this.get('controllers.component').send(actionName, params);
                    }
                },

                actions: {
                    save: function(params) {
                        this.actionWithCustomValidate('save', params);
                    },

                    saveAndGoBack: function(params) {
                        this.actionWithCustomValidate('saveAndGoBack', params);
                    },

                    add: function(params) {
                        this.actionWithCustomValidate('add', params);
                    },

                    addAndGoBack: function(params) {
                        this.actionWithCustomValidate('addAndGoBack', params);
                    }
                }
            });
        };
    }
);
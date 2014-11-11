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

                actionWithCustomValidate: function(actionName, params) {
                    if (this.validateForm()) {
                        return;
                    }

                    this.get('controllers.component').send(actionName, params);
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
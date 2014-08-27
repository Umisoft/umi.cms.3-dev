define(['App'],

    function(UMI) {
        'use strict';

        return function() {

            UMI.FormHelper = {
                getNestedProperties: function(elements, ignoreTypes) {
                    var propertyNames = [];
                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    if (Ember.typeOf(ignoreTypes) !== 'array') {
                        ignoreTypes = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (!ignoreTypes.contains(Ember.get(elements[i], 'type'))) {
                            var dataSource = Ember.get(elements[i], 'dataSource');
                            if (dataSource) {
                                propertyNames.push(dataSource);
                            }
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedPropertyNames = this.getNestedProperties(nestedElements, ignoreTypes);
                            propertyNames = propertyNames.concat(nestedPropertyNames);
                        }
                    }

                    return propertyNames;
                },

                filterLazyProperties: function(elements) {
                    var lazyProperties = [];

                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (elements[i].lazy) {
                            lazyProperties.push(Ember.get(elements[i], 'dataSource'));
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedLazyProperties = this.filterLazyProperties(nestedElements);
                            lazyProperties = lazyProperties.concat(nestedLazyProperties);
                        }
                    }

                    return lazyProperties;
                }
            };

            var objectFetch = function(routeData) {
                var meta = Ember.get(routeData, 'control.meta');
                var incompleteObject = Ember.get(routeData, 'object');
                var collectionName = incompleteObject.constructor.typeKey;
                var store = incompleteObject.get('store');
                var fields;
                var ignoreTypes = ['fieldset'];
                var promises = [];
                fields = UMI.FormHelper.getNestedProperties(Ember.get(meta, 'elements'), ignoreTypes);

                var request = UMI.OrmHelper.buildRequest(incompleteObject, fields);
                request.filters = {id: incompleteObject.get('id')};
                promises.push(store.updateCollection(collectionName, request));

                var lazyProperties = UMI.FormHelper.filterLazyProperties(Ember.get(meta, 'elements'));
                var manyRelationProperties = UMI.OrmHelper.getRelationProperties(incompleteObject, lazyProperties);

                for (collectionName in manyRelationProperties) {
                    if (manyRelationProperties.hasOwnProperty(collectionName)) {
                        promises.push(store.updateCollection(collectionName, {
                            fields: manyRelationProperties[collectionName]
                        }));
                    }
                }

                return Ember.RSVP.all(promises);
            };

            var FormControlPromiseService = Ember.Object.extend({
                execute: function(model) {
                    var defer = Ember.RSVP.defer();

                    objectFetch(model).then(
                        function(result) {
                            defer.resolve(result);
                        },
                        function(error) {
                            defer.reject(error);
                        }
                    );

                    return defer.promise;
                }
            });

            UMI.register('service:formControlPromise', FormControlPromiseService);
            UMI.inject('controller:action', 'editFormPromiseService', 'service:formControlPromise');

            UMI.FormControlController = Ember.ObjectController.extend(UMI.FormControllerMixin, {
                needs: ['component'],

                formElementsBinding: 'control.meta.elements',

                objectBinding: 'model.object',

                settings: function() {
                    var settings = {};
                    settings = this.get('controllers.component.settings');
                    return settings;
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

                validationErrors: function() {
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

                actionWithCustomValidate: function(actionName, params) {
                    var elements = this.inputElements();
                    elements = elements.mapBy('dataSource');
                    params.fields = elements;
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

            UMI.FormControlView = Ember.View.extend(UMI.FormViewMixin, {
                /**
                 * Шаблон формы
                 * @property layout
                 * @type String
                 */
                layoutName: 'partials/formControl',

                classNames: ['s-margin-clear', 's-full-height', 'umi-validator', 'umi-form-control'],

                willDestroyElement: function() {
                    this.get('controller').removeObserver('object.validErrors.@each');
                }
            });

            UMI.FieldFormControlView = Ember.View.extend(UMI.FieldMixin, {
                layout: function() {
                    var type = this.get('meta.type');
                    var layout = '<div><span class="umi-form-label">{{view.meta.label}}{{view.isRequired}}</span>' +
                        '</div>{{yield}}';

                    switch (type) {
                        case 'checkbox':
                            layout = '<div class="umi-form-element-without-label">{{yield}}{{view.isRequired}}</div>';
                    }

                    return Ember.Handlebars.compile(layout);
                }.property(),

                isRequired: function() {
                    var object = this.get('object');
                    var dataSource = this.get('meta.dataSource');
                    var validators;
                    if (object) {
                        validators = this.get('object').validatorsForProperty(dataSource);
                        if (Ember.typeOf(validators) === 'array' && validators.findBy('type', 'required')) {
                            return ' *';
                        }
                    }
                }.property('object'),

                isError: function() {
                    var dataSource = this.get('meta.dataSource');
                    var isValid = !!this.get('object.validErrors.' + dataSource);

                    return isValid;
                }.property('object.validErrors.@each'),

                wysiwygTemplate: function() {
                    return '{{view "htmlEditorCollection" object=view.object meta=view.meta}}';
                }.property(),

                selectTemplate: function() {
                    return '{{view "selectCollection" object=view.object meta=view.meta}}';
                }.property(),

                checkboxTemplate: function() {
                    return '{{view "checkboxCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                checkboxGroupTemplate: function() {
                    return '{{view "checkboxGroupCollectionElement" object=view.object meta=view.meta}}';
                }.property(),

                permissionsTemplate: function() {
                    return '{{view "permissions" object=view.object meta=view.meta}}';
                }.property(),

                objectRelationTemplate: function() {
                    return '{{view "objectRelationElement" object=view.object meta=view.meta}}';
                }.property(),

                pageRelationTemplate: function() {
                    return this.get('objectRelationTemplate');
                }.property()
            });
        };
    });

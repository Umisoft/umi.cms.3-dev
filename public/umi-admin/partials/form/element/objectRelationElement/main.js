define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.ObjectRelationElementView = Ember.View.extend(UMI.SerializedValue, {
            templateName: 'partials/objectRelationElement',

            classNames: ['row', 'collapse'],
            /**
             * @extended UMI.SerializedValue
             */
            path: 'displayName',
            /**
             * @extended UMI.SerializedValue
             */
            objectIsObservable: true,

            popupParams: function() {
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta')
                    },

                    viewParams: {
                        title: this.get('meta.label'),
                        popupType: 'objectRelation'
                    }
                };
            }.property(),

            actions: {
                clearValue: function() {
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    self.set('value', '');
                    object.set(property, '');

                    var parentView = this.get('parentView');
                    if (Ember.canInvoke(parentView, 'checkValidate')) {
                        parentView.checkValidate();
                    }
                },

                showPopup: function(params) {
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    object.clearValidateForProperty(property);
                    this.get('controller').send('showPopup', params);
                }
            },

            inputView: Ember.View.extend({
                focusOut: function() {
                    var parentView = this.get('parentView.parentView');
                    if (Ember.canInvoke(parentView, 'checkValidate')) {
                        parentView.checkValidate();
                    }
                },

                focusIn: function() {
                    var parentView = this.get('parentView.parentView');
                    if (Ember.canInvoke(parentView, 'checkValidate')) {
                        parentView.clearValidate();
                    }
                },

                type: 'text',

                classNames: ['umi-element-text'],

                objectBinding: 'parentView.object',

                metaBinding: 'parentView.meta',

                template: function() {
                    var template = '';
                    if (Ember.typeOf(this.get('object')) === 'instance') {
                        var template = '{{input type=view.type value=view.parentView.value placeholder=view.meta.placeholder name=view.meta.attributes.name disabled=true}}';
                    }
                    return Ember.Handlebars.compile(template);
                }.property()
            })
        });

        UMI.FormObjectRelationElementMixin = Ember.Mixin.create(UMI.FormElementMixin, UMI.FormElementValidateMixin, {
            classNames: ['small-12', 'large-4'],

            elementView: UMI.ObjectRelationElementView.extend({})
        });


        UMI.ObjectRelationLayoutController = Ember.ObjectController.extend({
            sideBarControl: Ember.computed.gt('collections.length', 1),

            collections: [],

            selectedCollection: null,

            getCollectionPropertyLabel: function(propertyName, collections, store) {
                if (Ember.typeOf(collections) === 'array') {
                    var collectionName = Ember.get(collections[0], 'id');
                    var model = store.modelFor(collectionName);
                    return Ember.get(model.metaForProperty('displayName'), 'options.' + propertyName);
                } else {
                    Ember.warn('Wrong argument type. Expected array.');
                }
            },

            tableControlSettings: function() {
                var self = this;
                var selectedCollectionId = self.get('selectedCollection.id');
                var object = self.get('model.object');
                var meta = self.get('model.meta');
                var property = object.get(Ember.get(meta, 'dataSource'));
                var activeObjectGuid;

                if (property) {
                    try {
                        property = JSON.parse(property);
                        activeObjectGuid = Ember.get(property, 'guid');
                    } catch (error) {
                        self.send('backgroundError', error);
                    }
                }

                var displayName = this.getCollectionPropertyLabel('displayName', self.get('collections'), self.get('store'));

                return {
                    control: {
                        collectionName: selectedCollectionId,
                        meta: {
                            defaultFields: [
                                'displayName'
                            ],
                            activeObjectGuid: activeObjectGuid,
                            form: {
                                elements: [
                                    {
                                        type: 'text',
                                        tag: 'input',
                                        id: 'displayName',
                                        label: displayName,
                                        attributes: {
                                            name: 'displayName',
                                            type: 'text',
                                            value: null
                                        },
                                        valid: true,
                                        errors: [],
                                        dataSource: 'displayName',
                                        value: null,
                                        validators: [],
                                        filters: []
                                    }
                                ]
                            }
                        },
                        behaviour: {
                            rowEvent: function(context, selectedObject) {
                                var dataSource = Ember.get(meta, 'dataSource');
                                var collectionName = Ember.get(selectedObject, 'constructor.typeKey');
                                var value = {
                                    collection: collectionName,
                                    guid: selectedObject.get('guid'),
                                    displayName: selectedObject.get('displayName')
                                };
                                object.set(dataSource, JSON.stringify(value));
                                context.send('closePopup');
                                //self.set('selectedCollection', self.get('collections').findBy('id', collectionName));
                            }
                        }
                    }
                };
            }.property('selectedCollection'),

            init: function() {
                var self = this;
                var object = self.get('object');
                var meta = self.get('meta');
                var dataSource = Ember.get(meta, 'dataSource');
                var computedProperty = object.get(dataSource);
                var collections = Ember.get(meta, 'collections');
                var collectionName;

                if (Ember.typeOf(collections) !== 'array') {
                    collections = [];
                }
                Ember.warn('Collection list is empty.', collections.length);
                self.set('collections', collections);

                if (computedProperty) {
                    try {
                        computedProperty = JSON.parse(computedProperty);
                        collectionName = Ember.get(computedProperty, 'meta.collectionName') || Ember.get(computedProperty, 'collection');
                        self.set('selectedCollection', collections.findBy('id', collectionName));
                    } catch (error) {
                        this.send('backgroundError', error);
                    }
                } else {
                    self.set('selectedCollection', collections[0]);
                }
            }
        });

        UMI.ObjectRelationLayoutView = Ember.View.extend({
            classNames: ['s-full-height'],
            templateName: 'partials/objectRelationLayout',
            sideMenu: Ember.View.extend({
                tagName: 'ul',
                classNames: ['side-nav'],
                templateName: 'partials/objectRelationLayout/sideMenu',
                itemView: Ember.View.extend({
                    tagName: 'li',
                    classNameBindings: ['isActive:active'],
                    isActive: function() {
                        return this.get('controller.selectedCollection.id') === this.get('item.id');
                    }.property('controller.selectedCollection'),
                    click: function() {
                        if (!this.get('isActive')) {
                            this.get('controller').set('selectedCollection', this.get('item'));
                        }
                    }
                })
            })
        });
    };
});
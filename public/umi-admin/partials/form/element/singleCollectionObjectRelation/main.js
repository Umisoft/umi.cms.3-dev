define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormSingleCollectionObjectRelationElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "singleCollectionObjectRelationElement" object=view.object meta=view.meta}}')
        });

        UMI.SingleCollectionObjectRelationElementView = UMI.ObjectRelationElementView.extend({
            templateName: 'partials/singleCollectionObjectRelationElement',

            classNames: ['row', 'collapse'],

            objectIsObservable: false,

            popupParams: function() {
                return {
                    templateParams: {
                        meta: this.get('meta')
                    },

                    viewParams: {
                        title: this.get('meta.label'),
                        popupType: 'singleCollectionObjectRelation'
                    }
                };
            }.property(),

            actions: {
                clearValue: function() {
                    var self = this;
                    self.set('value', '');
                },

                showPopup: function(params) {
                    this.get('controller').send('showPopup', params);
                }
            }
        });

        UMI.SingleCollectionObjectRelationLayoutController = UMI.ObjectRelationLayoutController.extend({
            tableControlSettings: function() {
                var self = this;
                var selectedCollectionId = self.get('selectedCollection.id');
                var meta = self.get('model.meta');
                var activeObjectGuid;
                activeObjectGuid = Ember.get(meta, 'value');

                return {
                    control: {
                        collectionName: selectedCollectionId,
                        meta: {
                            defaultFields: [
                                "displayName"
                            ],
                            activeObjectGuid: activeObjectGuid,
                            form: {
                                elements: [
                                    {
                                        type: "text",
                                        tag: "input",
                                        id: "displayName",
                                        label: "Имя отображения",//TODO: localize
                                        attributes: {
                                            name: "displayName",
                                            type: "text",
                                            value: null
                                        },
                                        valid: true,
                                        errors: [],
                                        dataSource: "displayName",
                                        value: null,
                                        validators: [],
                                        filters: []
                                    }
                                ]
                            }
                        },
                        behaviour: {
                            rowEvent: function(context, selectedObject) {
                                var value = selectedObject.get('guid');
                                Ember.set(meta, 'value', value);
                                context.send('closePopup');
                            }
                        }
                    }
                };
            }.property('selectedCollection'),

            init: function() {
                var self = this;
                var meta = self.get('meta');
                var collection = Ember.get(meta, 'collection');
                var collections = [];

                collections = [collection];
                Ember.warn('Collection list is empty.', collections.length);
                self.set('collections', collections);
                self.set('selectedCollection', collection);
            }
        });

        UMI.SingleCollectionObjectRelationLayoutView = UMI.ObjectRelationLayoutView.extend({});
    };
});
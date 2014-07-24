define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.PageRelationElementView = Ember.View.extend(UMI.SerializedValue, {
            templateName: 'partials/pageRelationElement',

            classNames: ['row', 'collapse'],
            /**
             * @extended UMI.SerializedValue
             */
            path: 'displayName',
            /**
             * @extended UMI.SerializedValue
             */
            objectIsObservable: true,

            popupParams: function(){
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta')
                    },
                    popupType: 'pageRelation'
                };
            }.property(),

            actions: {
                clearValue: function(){
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    self.set('value', '');
                    object.set(property, '');
                    object.validateProperty(property);
                },

                showPopup: function(params){
                    var self = this;
                    var object = self.get('object');
                    var property = this.get('meta.dataSource');
                    object.clearValidateForProperty(property);
                    this.get('controller').send('showPopup', params);
                }
            },

            inputView: Ember.View.extend(UMI.InputValidate, {
                type: "text",

                classNames: ['umi-element-text'],

                template: function(){
                    var template;
                    if(Ember.typeOf(this.get('object')) === 'instance'){
                        this.set('validator', 'collection');
                        var input = '{{input type=view.type value=view.parentView.value placeholder=view.meta.placeholder name=view.meta.attributes.name disabled=true}}';
                        var validate = this.validateErrorsTemplate();
                        template = input + validate;
                    }
                    return Ember.Handlebars.compile(template);
                }.property()
            })
        });


        UMI.PageRelationLayoutController = Ember.ObjectController.extend({
            sideBarControl: Ember.computed.gt('collections.length', 1),

            collections: [],

            selectedCollection: null,

            tableControlSettings: function(){
                var selectedCollectionId = this.get('selectedCollection.id');
                var object = this.get('model.object');
                var meta = this.get('model.meta');
                var property = object.get(Ember.get(meta, 'dataSource'));
                var activeObjectGuid;
                if(property){
                    try{
                        property = JSON.parse(property);
                        activeObjectGuid = Ember.get(property, 'guid');
                    } catch(error){
                        this.send('backgroundError', error);
                    }
                }

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
                                        label: "Имя отображения",
                                        attributes: {
                                            name: "displayName",
                                            type: "text",
                                            value: null
                                        },
                                        valid: true,
                                        errors: [ ],
                                        dataSource: "displayName",
                                        value: null,
                                        validators: [ ],
                                        filters: [ ]
                                    }
                                ]
                            }
                        },
                        behaviour: {
                            rowEvent: function(context, selectedObject){
                                var dataSource = Ember.get(meta, 'dataSource');
                                var value = {
                                    collectionName: Ember.get(selectedObject, 'conctructor.typeKey'),
                                    guid: selectedObject.get('guid'),
                                    displayName:  selectedObject.get('displayName')
                                };
                                object.set(dataSource, JSON.stringify(value));
                                context.send('closePopup');
                            }
                        }
                    }
                };
            }.property('selectedCollection'),

            init: function(){
                var self = this;
                var object = self.get('object');
                var meta = self.get('meta');
                var dataSource = Ember.get(meta, 'dataSource');
                var computedProperty = object.get(dataSource);
                var collections = Ember.get(meta, 'collections');
                var collectionName;

                if(Ember.typeOf(collections) !== 'array'){
                    collections = [];
                }
                Ember.warn('Collection list is empty.', collections.length);
                self.set('collections', collections);

                if(computedProperty){
                    try{
                        computedProperty = JSON.parse(computedProperty);
                        collectionName = Ember.get(computedProperty, 'meta.collectionName');
                        self.set('selectedCollection', collections.findBy('id', collectionName));
                    } catch(error){
                        this.send('backgroundError', error);
                    }
                } else{
                    self.set('selectedCollection', collections[0]);
                }
            }
        });

        UMI.PageRelationLayoutView = Ember.View.extend({
            classNames: ['s-full-height'],
            templateName: 'partials/pageRelationLayout',
            sideMenu: Ember.View.extend({
                tagName: 'ul',
                classNames: ['side-nav'],
                templateName: 'partials/pageRelationLayout/sideMenu',
                itemView: Ember.View.extend({
                    tagName: 'li',
                    classNameBindings: ['isActive:active'],
                    isActive: function(){
                        return this.get('controller.selectedCollection.id') === this.get('item.id');
                    }.property('controller.selectedCollection'),
                    click: function(){
                        if(!this.get('isActive')){
                            this.get('controller').set('selectedCollection', this.get('item'));
                        }
                    }
                })
            })
        });
    };
});
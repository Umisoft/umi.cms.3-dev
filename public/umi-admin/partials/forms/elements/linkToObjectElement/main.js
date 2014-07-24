define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.LinkToObjectElementView = Ember.View.extend({
            templateName: 'partials/linkToObjectElement',

            classNames: ['row', 'collapse'],

            popupParams: function(){
                return {
                    templateParams: {
                        object: this.get('object'),
                        meta: this.get('meta')
                    },
                    popupType: 'linkToObject'
                };
            }.property(),

            inputView: Ember.View.extend(UMI.InputValidate, {
                type: "text",

                classNames: ['umi-element-text'],

                template: function(){
                    var template;
                    if(Ember.typeOf(this.get('object')) === 'instance'){
                        this.set('validator', 'collection');
                        var dataSource = this.get('meta.dataSource');
                        var input = '{{input type=view.type value=view.object.' + dataSource + '.guid placeholder=view.meta.placeholder name=view.meta.attributes.name}}';
                        var validate = this.validateErrorsTemplate();
                        template = input + validate;
                    }
                    return Ember.Handlebars.compile(template);
                }.property()
            })
        });


        UMI.LinkToObjectLayoutController = Ember.ObjectController.extend({
            sideBarControl: Ember.computed.gt('collections.length', 1),
            collections: function(){
                var self = this;
                var meta = this.get('model.meta');
                var collectionList = ['structure', 'newsItem'];
                var collections = [];
                for(var i = 0; i < collectionList.length; i++){
                    collections.push({displayName: collectionList[i], id: collectionList[i]});
                }
                return collections;
            }.property('model'),
            selectedCollection: null,
            tableControlSettings: function(){
                var selectedCollectionId = this.get('selectedCollection.id');
                var object = this.get('model.object');
                var meta = this.get('model.meta');

                return {
                    control: {
                        collectionName: selectedCollectionId,
                        meta: {
                            defaultFields: [
                                "displayName"
                            ],
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
                                    guid: selectedObject.get('guid')
                                };
                                object.set(dataSource, value);
                                context.send('closePopup');
                            }
                        }
                    }
                };
            }.property('selectedCollection'),
            init: function(){
                var self = this;
                var collections = self.get('collections');
                if(Ember.typeOf(collections) !== 'array'){
                    collections = [];
                }
                var fistCollection = collections[0];
                Ember.warn('Collection list is empty.', collections.length);
                self.set('selectedCollection', collections[0]);
            }
        });

        UMI.LinkToObjectLayoutView = Ember.View.extend({
            classNames: ['s-full-height'],
            templateName: 'partials/linkToObjectLayout',
            sideMenu: Ember.View.extend({
                tagName: 'ul',
                classNames: ['side-nav'],
                templateName: 'partials/linkToObjectLayout/sideMenu',
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
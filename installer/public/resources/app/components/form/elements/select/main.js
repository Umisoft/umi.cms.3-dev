define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.SelectView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            optionLabelPath: 'content.displayName',
            optionValuePath: 'content.id',
            prompt: function(){
                return this.get('meta.placeholder');
            }.property('meta.placeholder'),
            content: function(){
                var self = this;
                var store = self.get('controller.store');
                var property = this.get('meta.dataSource');
                var object = this.get('object');
                var collection;
                var getCollection = function(relation){
                    collection = store.findAll(relation.type);
                };
                object.eachRelationship(function(name, relation){
                    if(name === property){
                        getCollection(relation);
                    }
                });
                return collection;
            }.property(),
            changeValue: function(){
                var selectObject = this.get('selection');
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var oldId = selectObject.get('id') || false;
                var newId = this.get('selectObject.id') || false;
                if(oldId !== newId){
                    object.set(property, selectObject);
                    object.send('becomeDirty');//TODO: Перенести в ядро REST Adapter
                } else if(object.get('isDirty')){
                    var changedAttributes = object.changedAttributes();
                    if(JSON.stringify(changedAttributes) === JSON.stringify({})){
                        object.send('rolledBack');
                    }
                }
            }.observes('value'),
            /**
             Связанный объект
             */
            selectObject: null,
            init: function(){
                this._super();
                var self = this;
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var promise = object.get(property);
                if(promise){
                    return promise.then(function(selectObject){
                        if(selectObject){
                            self.set('selectObject', selectObject);
                            self.set('selection', selectObject);
                        }
                    });
                }
            }
        });
    };
});

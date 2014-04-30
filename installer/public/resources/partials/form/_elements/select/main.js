define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.SelectView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            optionLabelPath: 'content.displayName',
            optionValuePath: 'content.id',
            prompt: function(){
                return this.get('meta.placeholder') || "Ничего не выбрано";
            }.property('meta.placeholder'),
            content: null,
            changeValue: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedObject = this.get('selection');
                object.set(property, selectedObject || undefined);
                object.changeRelationshipsValue(property, selectedObject ? selectedObject.get('id') : undefined);
            }.observes('value'),
            init: function(){
                this._super();
                var self = this;
                var promises = [];
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var store = self.get('controller.store');
                promises.push(object.get(property));

                var getCollection = function(relation){
                    promises.push(store.findAll(relation.type));
                };
                object.eachRelationship(function(name, relation){
                    if(name === property){
                        getCollection(relation);
                    }
                });

                return Ember.RSVP.all(promises).then(function(results){
                    Ember.set(object.get('loadedRelationshipsByName'), property, results[0] ? results[0].get('id') : undefined);
                    self.set('selection', results[0]);
                    self.set('content', results[1]);
                });
            },
            willDestroyElement: function(){
                //console.log('willDestroyElement');
            }
        });
    };
});
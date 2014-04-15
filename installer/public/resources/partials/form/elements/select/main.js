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
                var savedRelationId = this.get('savedRelationId');
                var oldId = savedRelationId || undefined;
                var newId = selectedObject ? selectedObject.get('id') : undefined;
                if(oldId !== newId){
                    if(newId){
                        object.set(property, selectedObject);
                    } else{
                        object.set(property, newId);
                    }
                    object.send('becomeDirty');//TODO: Перенести в ядро REST Adapter
                } else if(object.get('isDirty')){
                    var changedAttributes = object.changedAttributes();
                    if(JSON.stringify(changedAttributes) === JSON.stringify({})){
                        object.send('rolledBack');
                    }
                }
            }.observes('value'),
            /**
             Сохранённое значение
             */
            savedRelationId: null,
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

                object.on('didUpdate', function(){// TODO: Событие всплывает 2 раза подряд
                    self.set('savedRelationId', self.get('selection.id'));
                });

                return Ember.RSVP.all(promises).then(function(results){
                    self.set('savedRelationId', results[0] ? results[0].get('id') : undefined);
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

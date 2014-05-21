define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.SelectView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            optionLabelPath: function(){
                return 'content.label';
            }.property(),
            optionValuePath: function(){
                return 'content.value';
            }.property(),
            prompt: function(){
                return this.get('meta.placeholder') || "Ничего не выбрано";
            }.property('meta.placeholder'),
            content: null,
            init: function(){
                this._super();
                this.set('selection', this.get('object.choices').findBy('value', this.get('object.value')));
                this.set('content', this.get('object.choices'));
            }
        });

        UMI.SelectCollectionView = Ember.Select.extend(UMI.InputValidate, {
            attributeBindings: ['meta.dataSource:name'],
            optionLabelPath: function(){
                return this.get('meta.choices') ? 'content.label': 'content.displayName';
            }.property(),
            optionValuePath: function(){
                return this.get('meta.choices') ? 'content.value': 'content.id';
            }.property(),
            prompt: function(){
                return this.get('meta.placeholder') || "Ничего не выбрано";
            }.property('meta.placeholder'),
            content: null,
            changeValue: function(){
                var object = this.get('object');
                var property = this.get('meta.dataSource');
                var selectedObject = this.get('selection');
                var value;
                if(this.get('meta.choices')){
                    value = selectedObject ? selectedObject.value : undefined;
                    object.set(property, value);
                } else{
                    value = selectedObject ? selectedObject : undefined;
                    object.set(property, value);
                    object.changeRelationshipsValue(property, selectedObject ? selectedObject.get('id') : undefined);
                }
            }.observes('value'),
            init: function(){
                this._super();
                var self = this;
                var promises = [];
                var object = this.get('object');
                var property = this.get('meta.dataSource');

                // Эмпирическое правило: если нет choices то этот селект работает со связями.
                if(this.get('meta.choices')){
                    self.set('selection', this.get('meta.choices').findBy('value', object.get(property)));
                    self.set('content', this.get('meta.choices'));

                    this.addObserver('object.' + property, function(){
                        Ember.run.once(function(){
                            self.set('selection', self.get('meta.choices').findBy('value', object.get(property)));
                        });
                    });
                } else{
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
                }
            },
            willDestroyElement: function(){
                this.removeObserver('value');
                this.removeObserver('object.' + this.get('meta.dataSource'));
            }
        });
    };
});
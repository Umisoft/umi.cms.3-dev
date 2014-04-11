define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend({
            limit: 100,

            offset: 0,

            filter: function(){

            }.property(),

            query: function(){
                var query = {};
                var limit = this.get('limit');
                var filter = this.get('filter');
                var offset = this.get('offset');
                if(limit){
                    query.limit = limit;
                }
                if(filter){
                    query.filter = filter;
                }
                if(offset){
                    query.offset = offset * limit;
                }
                return query;
            }.property('limit', 'filter', 'offset'),

            objects: function(){
                var self = this;
                var query = this.get('query');
                var collectionName = self.get('controllers.component').settings.layout.collection;
                var objects = self.store.find(collectionName, query);
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: objects,
                    sortProperties: ['id'],
                    sortAscending: true
                });
            }.property('model.object'),

            queryChange: function(){
                var self = this;
                var query = this.get('query');
                //var parentId = this.get('model.object.id') !== 'root'? this.get('model.object.id') : 'null()';
                var collectionName = self.get('controllers.component').settings.layout.collection;
                var promise = self.store.find(collectionName, query);
                return self.store.find(collectionName, query).then(function(objects){
                    self.set('objects', objects);
                });
            }.observes('query'),

            actions: {
                sortByProperty: function(propertyName){
                    this.get('objects').set('sortProperties', [propertyName]);
                    this.get('objects').toggleProperty('sortAscending');
                }
            },

            needs: ['component']
        });
    };
});
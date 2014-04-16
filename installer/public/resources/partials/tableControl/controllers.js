define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend({
            limit: 100,
            total: function(){
                var collectionName = this.get('controllers.component').settings.layout.collection;
                var metaForCollection = this.get('store').metadataFor(collectionName);
                return metaForCollection.total || 0;
            }.property('objects.content.isFulfilled'),
            offset: 0,
            orderByProperty: null,
            order: function(){
                var orderByProperty = this.get('orderByProperty');
                if(orderByProperty){
                    var order = {};
                    order[orderByProperty.property] = orderByProperty.direction ? 'ASC' : 'DESC';
                    return order;
                }
            }.property('orderByProperty'),
            filters: function(){
                var filters = {};

                var collectionName = this.get('controllers.component').settings.layout.collection;
                var metaForCollection = this.get('store').metadataFor(collectionName);
                if(metaForCollection && metaForCollection.collectionType === 'hierarchic'){
                    var parentId = this.get('model.object.id') !== 'root' ? 'equals(' + this.get('model.object.id') + ')' : 'null()';
                    filters.parent = parentId;
                }

                return filters;
            }.property('content.object.id'),

            query: function(){
                var query = {};
                var limit = this.get('limit');
                var filters = this.get('filters');
                var offset = this.get('offset');
                var order = this.get('order');
                if(limit){
                    query.limit = limit;
                }
                if(filters){
                    query.filters = filters;
                }
                if(offset){
                    query.offset = offset * limit;
                }
                if(order){
                    delete query.offset;
                    query.orderBy = order;
                }
                return query;
            }.property('limit', 'filters', 'offset', 'order'),

            objects: function(){
                if(this.get('silentChange')){
                    return;
                }
                var self = this;
                var query = this.get('query');
                var collectionName = self.get('controllers.component').settings.layout.collection;
                var objects = self.store.find(collectionName, query);
                var orderByProperty = this.get('orderByProperty');
                var sortProperties = orderByProperty && orderByProperty.property ? orderByProperty.property : 'id';
                var sortAscending = orderByProperty && 'direction' in orderByProperty ? orderByProperty.direction : true;
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: objects,
                    sortProperties: [sortProperties],
                    sortAscending: sortAscending
                });
            }.property('content.object.id', 'query'),

            actions: {
                orderByProperty: function(propertyName, sortAscending){
                    this.set('orderByProperty', {'property' : propertyName, 'direction': sortAscending});
                }
            },

            /*clearParams: function(){
                console.log('clearParams');
                this.set('silentChange', true);
                this.setProperties({offset: 0, orderByProperty: null});
                this.set('silentChange', false);
            }.observes('content.object.id'),
            willDestroyElement: function(){
                console.log('willDestroyElement');
                this.removeObserver('content.object.id');
            },*/
            needs: ['component']
        });
    };
});
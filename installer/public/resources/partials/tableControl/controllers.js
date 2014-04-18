define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend({
            /**
             * Данные
             * @property objects
             */
            objects: null,
            /**
             * метод получает данные учитывая query параметры
             * @method getObjects
             */
            getObjects: function(){
                var self = this;
                var query = this.get('query');
                var collectionName = self.get('controllers.component.collectionName');
                var objects = self.store.find(collectionName, query);
                var orderByProperty = this.get('orderByProperty');
                var sortProperties = orderByProperty && orderByProperty.property ? orderByProperty.property : 'id';
                var sortAscending = orderByProperty && 'direction' in orderByProperty ? orderByProperty.direction : true;
                var data = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: objects,
                    sortProperties: [sortProperties],
                    sortAscending: sortAscending
                });
                this.set('objects', data);
            },
            /**
             * Количество объектов на странице
             * @property limit
             */
            limit: 100,
            /**
             * Индекс первого объекта на странице
             * @property offset
             */
            offset: 0,
            /**
             * Количество объектов во всей коллекции
             * @property total
             */
            total: 0,
            /**
             * Свойство по которому необходимо выполнить фильтрацию
             * @property orderByProperty
             * @example {'property' : propertyName, 'direction': sortAscending}
             */
            orderByProperty: null,
            /**
             * Вычисляемое свойство возвращающее параметры сортировки
             * @property order
             */
            order: function(){
                var orderByProperty = this.get('orderByProperty');
                if(orderByProperty){
                    var order = {};
                    order[orderByProperty.property] = orderByProperty.direction ? 'ASC' : 'DESC';
                    return order;
                }
            }.property('orderByProperty'),
            /**
             * Свойства фильтрации
             * @property filters
             */
            filterParams: null,
            /**
             * Вычисляемое свойство фильтрации
             * @property filters
             */
            filters: function(){
                var filters = {};
                var filterParams = this.get('filterParams');
                for(var filter in filterParams){
                    if(filter === 'parent'){
                        filters[filter] = filterParams[filter] !== 'root' ? 'equals(' + this.get('model.object.id') + ')' : 'null()';
                    }
                }
                return filters;
            }.property('filterParams'),
            /**
             * Вычисляемое свойство параметров запроса коллекции
             * @property query
             */
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
                    query.orderBy = order;
                }
                return query;
            }.property('limit', 'filters', 'offset', 'order'),
            /**
             * Метод вызывается при смене контекста (компонента).
             * Сбрасывает значения фильтров,вызывает метод getObjects, вычисляет total
             * @method contextChanged
             */
            contextChanged: function(){
                // Вычисляем фильтр в зависимости от типа коллекции
                var collectionName = this.get('controllers.component.collectionName');
                var metaForCollection = this.get('store').metadataFor(collectionName);
                var contextFilter = {};
                if(metaForCollection && metaForCollection.collectionType === 'hierarchic'){
                    contextFilter.parent = this.get('model.object.id');
                }
                // Сбрасываем параметры запроса, не вызывая обсервер query
                this.set('withoutChangeQuery', true);
                this.setProperties({offset: 0, orderByProperty: null, total: 0, filterParams: contextFilter});
                this.set('withoutChangeQuery', false);

                this.getObjects();
                Ember.run.next(this, function(){
                    var self = this;
                    this.get('objects.content').then(function(){
                        var collectionName = self.get('controllers.component.collectionName');
                        var metaForCollection = self.get('store').metadataFor(collectionName);
                        self.set('total', metaForCollection.total);
                    });
                });
            }.observes('content.object.id').on('init'),

            /**
             * Метод вызывается при изменении параметров запроса.
             * @method queryChanged
             */
            queryChanged: function(){
                if(this.get('withoutChangeQuery')){
                    return;
                }
                Ember.run.once(this, 'getObjects');
            }.observes('query'),

            actions: {
                orderByProperty: function(propertyName, sortAscending){
                    this.set('orderByProperty', {'property' : propertyName, 'direction': sortAscending});
                }
            },

            needs: ['component']
        });
    };
});
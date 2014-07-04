define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend(UMI.i18nInterface,{
            componentNameBinding: 'controllers.component.name',
            dictionaryNamespace: 'tableControl',
            localDictionary: function(){
                var contentControls = this.get('controllers.component.contentControls') || [];
                var filter = contentControls.findBy('name', 'filter') || {};
                return filter.i18n;
            }.property(),
            /**
             * Данные
             * @property objects
             */
            objects: null,
            objectChange: function(){
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted'),

            updateObjectDeleted: function(){//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item){
                    if(item && item.get('isDeleted')){
                        objects.get('content.content.content').removeObject(item);
                    }
                });
            },

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
            limit: 25,
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
             * Список отображаемых полей принадлежащих объекту
             */
            nativeFieldsList: null,
            /**
             * Вычисляемое свойство списка полей принадлежащих объекту
             * @property fields
             */
            nativeFields: function(){
                var fieldsList = this.get('fieldsList');
                if(Ember.typeOf(fieldsList) === 'array' && fieldsList.length){
                    fieldsList = fieldsList.join(',');
                    return fieldsList;
                }
            }.property('nativeFieldsList'),
            /**
             * Список полей имеющих связь belongsTo
             */
            relatedFieldsList: null,
            /**
             * Вычисляемое свойство возвращающее поля belongsTo
             * @property fields
             */
            relatedFields: function(){
                var relatedFields = this.get('relatedFieldsList');
                if(Ember.typeOf(relatedFields) === 'object' && JSON.stringify(relatedFields) !== '{}'){
                    return relatedFields;
                }
            }.property('relatedFieldsList'),

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
                var nativeFields = this.get('nativeFields');
                var relatedFields = this.get('relatedFields');
                var limit = this.get('limit');
                var filters = this.get('filters');
                var offset = this.get('offset');
                var order = this.get('order');
                if(nativeFields){
                    query.fields = nativeFields;
                }
                if(relatedFields){
                    query['with'] = relatedFields;
                }
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
            }.property('limit', 'filters', 'offset', 'order', 'nativeFields'),

            /**
             * Метод вызывается при смене контекста (компонента).
             * Сбрасывает значения фильтров,вызывает метод getObjects, вычисляет total
             * @method contextChanged
             */
            contextChanged: function(){
                var store = this.get('store');
                // Вычисляем фильтр в зависимости от типа коллекции
                var collectionName = this.get('controllers.component.collectionName');
                var metaForCollection = store.metadataFor(collectionName);
                var contextFilter = {};// TODO: Убрать в условии значение filter
                if(metaForCollection && metaForCollection.collectionType === 'hierarchic' && this.get('container').lookup('route:action').get('context.action').name !== 'filter'){
                    contextFilter.parent = this.get('model.object.id');
                }

                //TODO: check user configurations
                var modelForCollection = store.modelFor(collectionName);
                var fieldsList = this.get('viewSettings.form.elements') || [];
                var defaultFields = this.get('viewSettings.defaultFields') || [];
                var i;
                for(i = 0; i < fieldsList.length; i++){
                    if(!defaultFields.contains(fieldsList[i].id)){
                        fieldsList.splice(i, 1);
                        --i;
                    }
                }

                var nativeFieldsList = [];
                var relatedFieldsList = {};
                modelForCollection.eachRelationship(function(name, relatedModel){
                    var i;
                    var relatedModelFields = {};
                    relatedModelFields = [];
                    var relatedModelDataSource;
                    if(relatedModel.kind === 'belongsTo' && fieldsList.findBy('dataSource', name)){
                        for(i = 0; i < fieldsList.length; i++){
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if(relatedModelDataSource === name){
                                fieldsList.splice(i, 1);
                                --i;
                            } else if(relatedModelDataSource.indexOf(name + '.', 0) === 0){
                                relatedModelFields.push(relatedModelDataSource.slice(name.length + 1));
                                //fieldsList.splice(i, 1);
                                //--i;
                            }
                        }
                        relatedFieldsList[name] = relatedModelFields.join(',');
                    } else if(relatedModel.kind === 'hasMany' || relatedModel.kind === 'manyToMany'){
                        for(i = 0; i < fieldsList.length; i++){
                            relatedModelDataSource = fieldsList[i].dataSource;
                            if(relatedModelDataSource === name || relatedModelDataSource.indexOf(name + '.', 0) === 0){
                                fieldsList.splice(i, 1);
                                --i;
                            }
                        }
                        //Ember.assert('Поля с типом hasMany и manyToMany недопустимы в фильтре.'); TODO: uncomment
                    }
                });

                nativeFieldsList = fieldsList.mapBy('dataSource') || [];

                // Сбрасываем параметры запроса, не вызывая обсервер query
                this.set('withoutChangeQuery', true);
                this.setProperties({nativeFieldsList: nativeFieldsList, relatedFieldsList: relatedFieldsList, offset: 0, orderByProperty: null, total: 0, filterParams: contextFilter});
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

            /**
             * Возвращает список кнопок контекстного меню
             * @property contextToolbar
             * return Array
             */
            contextToolbar: function(){
                var contentControls = this.get('controllers.component.contentControls') || [];
                var filter = contentControls.findBy('name', 'filter') || {};
                return filter.contextToolbar;
            }.property('model'),

            /**
             * Возвращает toolbar
             * @property toolbar
             * return Array
             */
            toolbar: function(){
                var toolbar = this.get('controllers.component.contentControls') || [];
                toolbar = toolbar.findBy('name', 'filter') || {};
                toolbar = toolbar.toolbar || [];
                return toolbar;
            }.property('model'),

            actions: {
                orderByProperty: function(propertyName, sortAscending){
                    this.set('orderByProperty', {'property' : propertyName, 'direction': sortAscending});
                }
            },

            needs: ['component'],

            itemController: 'tableControlContextToolbarItem'
        });

        UMI.TableControlContextToolbarItemController = Ember.ObjectController.extend({
            objectBinding: 'content',
            componentNameBinding: 'parentController.componentName'
        });

    };
});
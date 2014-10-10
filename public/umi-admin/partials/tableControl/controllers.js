define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TableControlMixin = Ember.Mixin.create(UMI.i18nInterface, {
            /**
             * Имя коллекции объектов
             * @abstract
             * @property collectionName
             * @type {String}
             */
            collectionName: null,

            /**
             * @property dictionaryNamespace
             * @type {String}
             */
            dictionaryNamespace: 'tableControl',

            /**
             * @property hasContextMenu
             * @type {Boolean}
             */
            hasContextMenu: false,

            /**
             * @property localDictionary
             * @type {ComputedProperty}
             */
            localDictionary: function() {
                var filter = this.get('control') || {};
                return filter.i18n;
            }.property(),

            /**
             * Данные
             * @property objects
             * @type {Array}
             */
            objects: null,

            objectChange: function() {
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted'),

            updateObjectDeleted: function() {//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item) {
                    if (item && item.get('isDeleted')) {
                        objects.removeObject(item);
                    }
                });
            },

            /**
             * метод получает данные учитывая query параметры
             * @method getObjects
             */
            getObjects: function() {
                var self = this;
                var store = self.get('store');
                self.send('showLoader');
                var query = this.get('query') || {};
                var collectionName = self.get('collectionName');
                var objects = self.store.updateCollection(collectionName, query);
                var orderByProperty = self.get('orderByProperty');
                var sortProperties = orderByProperty && orderByProperty.property ? orderByProperty.property : 'id';
                var sortAscending = orderByProperty && 'direction' in orderByProperty ? orderByProperty.direction :
                    true;

                objects.then(function() {
                    self.send('hideLoader');
                    var metaForCollection = store.metadataFor(collectionName);
                    self.set('total', metaForCollection.total);
                });
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
             * @type {Number}
             */
            limit: 25,

            /**
             * Индекс первого объекта на странице
             * @property offset
             * @type {Number}
             */
            offset: 0,

            /**
             * Количество объектов во всей коллекции
             * @property total
             * @type {Number}
             */
            total: 0,

            /**
             * Свойство по которому необходимо выполнить фильтрацию
             * @property orderByProperty
             * @type {Object}
             * @example {'property' : propertyName, 'direction': sortAscending}
             */
            orderByProperty: null,

            /**
             * Вычисляемое свойство возвращающее параметры сортировки
             * @property order
             * @type {ComputedProperty}
             */
            order: function() {
                var orderByProperty = this.get('orderByProperty');
                if (orderByProperty) {
                    var order = {};
                    order[orderByProperty.property] = orderByProperty.direction ? 'ASC' : 'DESC';
                    return order;
                }
            }.property('orderByProperty'),

            /**
             * Список отображаемых полей принадлежащих объекту
             * @property nativeFieldsList
             * @type {Array}
             */
            nativeFieldsList: null,

            /**
             * Вычисляемое свойство списка полей принадлежащих объекту
             * @property fields
             * @type {ComputedProperty}
             */
            nativeFields: function() {
                var nativeFieldsList = this.get('nativeFieldsList');
                if (Ember.typeOf(nativeFieldsList) === 'array' && nativeFieldsList.length) {
                    nativeFieldsList = nativeFieldsList.join(',');
                    return nativeFieldsList;
                }
            }.property('nativeFieldsList'),

            /**
             * Список полей имеющих связь belongsTo
             * @property relatedFieldsList
             * @type {Array}
             */
            relatedFieldsList: null,

            /**
             * Вычисляемое свойство возвращающее поля belongsTo
             * @property fields
             * @type {ComputedProperty}
             */
            relatedFields: function() {
                var relatedFields = this.get('relatedFieldsList');

                if (Ember.typeOf(relatedFields) === 'object' && JSON.stringify(relatedFields) !== '{}') {
                    return relatedFields;
                }
            }.property('relatedFieldsList'),

            /**
             * Свойства фильтрации коллекции
             * @property collectionFilterParams
             * @type {Object}
             */
            collectionFilterParams: null,

            /**
             * Свойства фильтрации
             * @property filters
             * @type {Object}
             */
            filterParams: null,

            /**
             * Вычисляемое свойство фильтрации
             * @property filters
             * @type {ComputedProperty}
             */
            filters: function() {
                var filters = {};
                var filter;
                var filterParams = this.get('filterParams') || {};
                var collectionFilterParams = this.get('collectionFilterParams') || {};

                for (filter in collectionFilterParams) {
                    if (collectionFilterParams.hasOwnProperty(filter)) {
                        if (Ember.typeOf(collectionFilterParams[filter]) === 'string' && !collectionFilterParams[filter].length) {
                            delete filters[filter];
                        } else {
                            filters[filter] = collectionFilterParams[filter];
                        }
                    }
                }

                for (filter in filterParams) {
                    if (filterParams.hasOwnProperty(filter)) {
                        if (Ember.typeOf(filterParams[filter]) === 'string' && !filterParams[filter].length) {
                            delete filters[filter];
                        } else {
                            filters[filter] = filterParams[filter];
                        }
                    }
                }

                return filters;
            }.property('filterParams.@each', 'collectionFilterParams.@each'),

            setFilters: function(property, filter) {
                this.propertyWillChange('filterParams');
                this.set('filterParams', null);
                var filterParams = {};
                filterParams[property] = filter;
                this.set('filterParams', filterParams);
                this.propertyDidChange('filterParams');
            },

            /**
             * свойство, формирующее запрос коллекции
             * @property query
             */
            query: null,

            queryChange: function() {
                if (this.get('withoutChangeQuery')) {
                    return;
                }
                Ember.run.once(this, 'buildQuery');
            }.observes('limit', 'filters', 'offset', 'order', 'nativeFields', 'relatedFields').on('init'),

            buildQuery: function() {
                var query = {};
                var nativeFields = this.get('nativeFields');
                var relatedFields = this.get('relatedFields');
                var limit = this.get('limit');
                var filters = this.get('filters');
                var offset = this.get('offset');
                var order = this.get('order');
                if (nativeFields) {
                    query.fields = nativeFields;
                }
                if (relatedFields) {
                    query['with'] = relatedFields;
                }
                if (limit) {
                    query.limit = limit;
                }
                if (filters) {
                    query.filters = filters;
                }
                if (offset) {
                    query.offset = offset * limit;
                }
                if (order) {
                    query.orderBy = order;
                }
                this.set('query', query);
            },

            /**
             * Список всех полей
             * @property allFields
             */
            allFieldsBinding: 'control.meta.form.elements',

            /**
             * Список полей отображаемых по умолчанию
             * @property defaultFields
             */
            defaultFieldsBinding: 'control.meta.defaultFields',

            /**
             * Список ингнорируемых для вывода полей
             * @property ignoreFields
             */
            ignoredFields: null,

            /**
             * Выбранные к отображению поля
             * @property selectedFields
             * @type {Array}
             */
            selectedFields: null,

            /**
             * Отображаемые в фильтре поля (столбцы)
             * @property visibleFields
             */
            visibleFields: null,

            visibleFieldsCompute: function() {
                Ember.run.once(this, function() {
                    this.set('visibleFields', this.getVisibleFields());
                });
            }.observes('allFields', 'defaultFields', 'ignoredFields', 'selectedFields').on('init'),

            visibleFieldsChanged: function() {
                var nativeFieldsList = [];

                var visibleFields = this.get('visibleFields') || [];
                var store = this.get('store');
                var collectionName = this.get('collectionName');
                var modelForCollection = store.modelFor(collectionName);

                modelForCollection.eachAttribute(function(name) {
                    var selfProperty = visibleFields.findBy('dataSource', name);

                    if (selfProperty) {
                        nativeFieldsList.push(selfProperty.dataSource);
                    } else if (name === 'active') {
                        nativeFieldsList.push('active');
                    }
                });

                var relatedFieldsList = {};
                modelForCollection.eachRelationship(function(name, relatedModel) {
                    var i;
                    var relatedModelDataSource;
                    if (relatedModel.kind === 'belongsTo') {
                        for (i = 0; i < visibleFields.length; i++) {
                            relatedModelDataSource = Ember.get(visibleFields[i], 'dataSource');
                            if (relatedModelDataSource === name) {
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                            } else if (relatedModelDataSource.indexOf(name + '.', 0) === 0) {
                                relatedFieldsList[name] = relatedFieldsList[name] || [];
                                relatedFieldsList[name].push(relatedModelDataSource.slice(name.length + 1));
                            }
                        }
                    }

                    if (Ember.typeOf(relatedFieldsList[name]) === 'array') {
                        relatedFieldsList[name] = relatedFieldsList[name].join(',') || 'displayName';
                    }
                });

                this.setProperties(
                    {
                        nativeFieldsList: nativeFieldsList,
                        relatedFieldsList: relatedFieldsList
                    }
                );
            }.observes('visibleFields'),

            getVisibleFields: function() {
                var allFields = this.get('allFields');
                var defaultFields = this.get('defaultFields');
                var visibleFields = [];

                var i;
                for (i = 0; i < allFields.length; i++) {
                    if (defaultFields.contains(Ember.get(allFields[i], 'dataSource'))) {
                        visibleFields.push(allFields[i]);
                    }
                }

                this.filterManyRelation(visibleFields);
                return visibleFields;
            },

            filterManyRelation: function(fieldsList) {
                var store = this.get('store');
                var collectionName = this.get('collectionName');
                var modelForCollection = store.modelFor(collectionName);

                modelForCollection.eachRelationship(function(name, relatedModel) {
                    var relatedModelDataSource;
                    if (relatedModel.kind === 'hasMany' || relatedModel.kind === 'manyToMany') {
                        for (var i = 0; i < fieldsList.length; i++) {
                            relatedModelDataSource = Ember.get(fieldsList[i], 'dataSource');
                            if (relatedModelDataSource === name || relatedModelDataSource.indexOf(name + '.', 0) === 0) {
                                Ember.warn('Поля с типом hasMany и manyToMany недопустимы в фильтре.');
                                fieldsList.splice(i, 1);
                                --i;
                            }
                        }
                    }
                });
            },

            /**
             * Метод вызывается при смене контекста (компонента).
             * Сбрасывает значения фильтров,вызывает метод getObjects, вычисляет total
             * @method updateContent
             */
            updateContent: function() {
                var store = this.get('store');
                var collectionName = this.get('collectionName');
                var modelForCollection = store.modelFor(collectionName);

                // Вычисляем фильтр в зависимости от типа коллекции
                var collectionFilterParams = this.get('control.params.filter') || {};
                modelForCollection.eachAttribute(function(name) {
                    if (name === 'trashed' && !Ember.get(collectionFilterParams, 'trashed')) {
                        collectionFilterParams.trashed = 'equals(0)';
                    }
                });

                // Сбрасываем параметры запроса, не вызывая обсервер query
                this.set('withoutChangeQuery', true);
                this.setProperties(
                    {
                        offset: 0,
                        limit: 25,
                        orderByProperty: null,
                        collectionFilterParams: collectionFilterParams
                    }
                );
                this.set('withoutChangeQuery', false);
            },

            /**
             * Метод-обсервер, вызывающий updateContent при смене контекста
             * @abstract
             */
            contextChange: null,

            /**
             * Метод вызывается при изменении параметров запроса.
             * @method queryChanged
             */
            queryChanged: function() {
                Ember.run.once(this, 'getObjects');
            }.observes('query'),

            /**
             * Возвращает список кнопок контекстного меню
             * @property contextToolbar
             * return Array
             */
            contextToolbar: function() {
                var filter = this.get('control') || {};
                return filter.contextToolbar;
            }.property('model'),

            /**
             * Возвращает toolbar
             * @property toolbar
             * return Array
             */
            toolbar: function() {
                var filter = this.get('control') || {};
                return filter.toolbar || [];
            }.property('model'),

            actions: {
                orderByProperty: function(propertyName, sortAscending) {
                    this.set('orderByProperty', {'property': propertyName, 'direction': sortAscending});
                }
            }
        });

        UMI.TableControlController = Ember.ObjectController.extend(UMI.TableControlMixin, {
            needs: ['component'],

            componentNameBinding: 'controllers.component.name',

            hasContextMenu: true,

            hasPopup: true,

            collectionName: function() {
                var dataSource = this.get('controllers.component.dataSource.name');
                if (!dataSource) {
                    dataSource = this.get('controllers.component.selectedContext');
                }
                return dataSource;
            }.property('controllers.component.dataSource.name', 'controllers.component.selectedContext'),

            itemController: 'tableControlContextToolbarItem',

            updateObjectDeleted: function() {//TODO: Реализация плохая: множественное всплытие события
                var objects = this.get('objects');
                objects.forEach(function(item) {
                    if (item && item.get('isDeleted')) {
                        objects.get('content.content').removeObject(item);
                    }
                });
            },

            contextChange: function() {
                this.updateContent();
            }.observes('content.object.id').on('init'),

            objectChange: function() {
                Ember.run.once(this, 'updateObjectDeleted');
            }.observes('objects.@each.isDeleted'),

            popupParams: function() {
                var self = this;

                return {
                    templateParams: {
                        allFields: self.get('allFields'),

                        defaultFields: self.get('defaultFields'),

                        visibleFields: self.get('visibleFields'),

                        tableController: self
                    },

                    viewParams: {
                        title: UMI.i18n.getTranslate('Selected fields', 'tableControl'),

                        popupType: 'tableControl',

                        width: 'auto',

                        height: '400'
                    }
                };
            }.property('allFields', 'visibleFields', 'defaultFields'),

            actions: {
                openColumnConfiguration: function() {
                    this.get('controllers.component').send('showPopup', this.get('popupParams'));
                }
            }
        });

        UMI.TableControlContextToolbarItemController = Ember.ObjectController.extend({
            objectBinding: 'content',
            componentNameBinding: 'parentController.componentName',

            isSelected: function() {
                var objectGuid = this.get('object.guid');
                return objectGuid === this.get('parentController.control.meta.activeObjectGuid');
            }.property('parentController.control.meta.activeObjectGuid')
        });

        UMI.TableControlSharedController = Ember.ObjectController.extend(UMI.TableControlMixin, {
            collectionName: function() {
                return this.get('control.collectionName');
            }.property('control.collectionName'),

            contextChange: function() {
                this.updateContent();
            }.observes('collectionName').on('init'),

            actions: {
                executeBehaviour: function(behaviourName, object) {
                    var behaviour = this.get('control.behaviour.' + behaviourName);

                    if (Ember.typeOf(behaviour) === 'function') {
                        behaviour(this, object);
                    } else {
                        Ember.warn('Behaviour for row click did not set.');
                    }
                }
            }
        });
    };
});
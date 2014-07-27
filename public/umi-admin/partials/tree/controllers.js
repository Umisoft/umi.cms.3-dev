define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
            /**
             * Импортируемые контроллеры
             * @property needs
             */
            needs: ['component', 'context'],
            /**
             * Имя коллекции
             * @property collectionName
             */
            collectionNameBinding: 'controllers.component.dataSource.name',
            /**
             * Определяет 'trasheble' коллекцию
             * @property isTrashableCollection
             */
            isTrashableCollection: null,
            /**
             * Запрашиваемые свойства объекта
             * @property properties
             */
            properties: function(){
                var properties = ['displayName', 'order', 'active', 'childCount'];
                var collectionName = this.get('collectionName');
                var model = this.get('store').modelFor(collectionName);
                var modelFields = Ember.get(model, 'fields');
                modelFields = modelFields.keys.list;
                for(var i = 0; i < properties.length; i++){
                    if(!modelFields.contains(properties[i])){
                        properties.splice(i, 1);
                        --i;
                    }
                }
                this.set('isTrashableCollection', modelFields.contains('trashed'));
                return properties;
            }.property('model'),
            /**
             * Контекстное меню
             * @property contentToolbar
             */
            contextToolbar: function(){
                return Ember.get(this.get('controllers.component'), 'sideBarControl.contextToolbar');
            }.property('controllers.component.sideBarControl.contextToolbar'),
            /**
             * Возвращает корневой элемент
             * @property root
             */
            root: function(){
                var collectionName = this.get('collectionName');
                var sideBarControl = this.get('controllers.component.sideBarControl');
                if(!sideBarControl){
                    return;
                }
                var self = this;
                var Root = Ember.Object.extend({
                    displayName: Ember.get(sideBarControl, 'params.rootNodeName') || '',
                    root: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    typeKey: collectionName,
                    childCount: 1
                });
                var root = Root.create({});
                return root;
            }.property('model'),

            params: function(){
                return {
                    properties: this.get('properties'),
                    isTrashableCollection: this.get('isTrashableCollection'),
                    contextToolbar: this.get('contextToolbar')
                };
            }.property('properties', 'isTrashableCollection', 'contextToolbar'),

            /**
             * Активный контекст
             * @property activeContext
             */
            activeContext: function(){
                return this.get('controllers.context.model');
            }.property('controllers.context.model'),

            /**
             * Ветви дерева, которые требуется открыть при сменене контекста
             * @property needsExpandedItems
             */
            needsExpandedItems: '',

            actions: {
                /**
                 Сохранение результата drag and drop
                 @method updateSortOrder
                 @param String id ID перемещаемого объекта
                 @param String id ID нового родителя перемещаемого объекта
                 @param String id ID элемента после которого вставлен перемещаемый объект
                 @param Array Массив nextSibling следующие обьекты за перемещаемым объектом
                 */
                updateSortOrder: function(id, parentId, prevSiblingId, nextSibling){
                    var self = this;
                    var type = this.get('collectionName');
                    var ids = nextSibling || [];
                    var moveParams = {};
                    var resource;
                    var sibling;
                    var node;
                    var parent;
                    var oldParentId;
                    var models = this.store.all(type);

                    node = models.findBy('id', id);
                    moveParams.object = {
                        'id': node.get('id'),
                        'version': node.get('version')
                    };
                    oldParentId = node.get('parent.id') || 'root';


                    if(parentId && parentId !== 'root'){
                        parent = models.findBy('id', parentId);
                        moveParams.branch = {
                            'id': parent.get('id'),
                            'version': parent.get('version')
                        };
                    }
                    if(prevSiblingId){
                        sibling = models.findBy('id', prevSiblingId);
                        moveParams.sibling = {
                            'id': sibling.get('id'),
                            'version': sibling.get('version')
                        };
                    }

                    resource = this.get('controllers.component.settings.actions.move.source');
                    $.ajax({'type': 'POST', 'url': resource, 'data': JSON.stringify(moveParams), 'dataType': 'json', 'contentType': 'application/json'}).then(
                        function(){
                            ids.push(id);
                            var parentsUpdateRelation = [];
                            if(parentId !== oldParentId){
                                if(parentId && parentId !== 'root'){
                                    ids.push(parentId);
                                    parentsUpdateRelation.push(parentId);
                                }
                                if(oldParentId && oldParentId !== 'root'){
                                    ids.push(oldParentId);
                                    parentsUpdateRelation.push(oldParentId);
                                }
                            }
                            self.store.findByIds(type, ids).then(function(nodes){
                                nodes.invoke('reload');
                                var parent;
                                var promises = [];
                                for(var i = 0; i < parentsUpdateRelation.length; i++){
                                    parent = models.findBy('id', parentsUpdateRelation[i]);
                                    parent.get('children').then(function(children){
                                        promises.push(children.reloadLinks());
                                    });
                                }

                                if(parentId !== oldParentId && (parentId === 'root' || oldParentId === 'root')){
                                    self.get('root')[0].updateChildren(id, parentId);
                                }
                            });
                        },
                        function(error){
                            self.send('backgroundError', error);
                        }
                    );
                }
            }
        });

        UMI.TreeControlItemController = Ember.ObjectController.extend({
            needs: ['treeControl'],

            item: Ember.computed.alias('model'),
            /**
             * Ссылка на редактирование елемента
             * @property editLInk
             */
            editLink: function(){
                var link = this.get('item.meta.editLink');
                return link;
            }.property('item'),

            /**
             * Сохранённое имя отображения объекта
             * @property savedDisplayName
             */
            savedDisplayName: function(){
                if(this.get('item.id') === 'root'){
                    return this.get('item.displayName');
                } else{
                    return this.get('item._data.displayName');
                }
            }.property('item.currentState.loaded.saved'),//TODO: Отказаться от использования _data

            needsExpandedItemsBinding: 'controllers.treeControl.needsExpandedItems',

            /**
             * Для активного объекта добавляется класс active
             * @property active
             */
            isActiveContext: function(){
                return this.get('controllers.treeControl.activeContext.id') === this.get('item.id');
            }.property('controllers.treeControl.activeContext.id'),

            childrenList: function(){
                return this.getChildren();
            }.property(),

            hasChildren: Ember.computed.bool('item.childCount'),

            params: Ember.computed.alias('controllers.treeControl.params'),
            /**
             *
             * @returns {*}
             */
            getChildren: function(){
                var model = this.get('item');
                var collectionName = model.get('typeKey') || model.constructor.typeKey;
                var promise;
                var self = this;

                var properties = self.get('params.properties').join(',');
                var parentId;
                if(model.get('id') === 'root'){
                    parentId = 'null()';
                } else{
                    parentId = model.get('id');
                }
                var requestParams = {'filters[parent]': parentId, 'fields': properties};
                if(self.get('params.isTrashableCollection')){
                    requestParams['filters[trashed]'] = 'equals(0)';
                }
                promise = this.get('store').updateCollection(collectionName, requestParams);
                /*setTimeout(function(){
                    promise.then(function(){
                        var iScroll = self.get('treeControlView.iScroll');
                        if(iScroll){
                            setTimeout(function(){
                                iScroll.refresh();
                            }, 100);
                        }
                    });
                }, 0);*/
                var promiseArray =  Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: promise,
                    sortProperties: ['order'],
                    sortAscending: true
                });
                return promiseArray;
            },

            init: function(){
                this._super();
                var self = this;
                var model = this.get('item');

                if(model.get('id') === 'root'){
                    self.get('controllers.treeControl.controllers.component').on('needReloadRootElements', function(event, object){
                        if(event === 'add'){
                            self.get('childrenList').pushObject(object);
                        } else if(event === 'remove'){
                            self.get('childrenList').removeObject(object);
                        }
                    });
                } else{
                    this.get('item').on('needReloadHasMany', function(event, object){
                        if(event === 'add'){
                            self.get('childrenList').pushObject(object);
                        }
                        //'item.didUpdate',
                        //this.incrementProperty('reloadChildren');
                        /*model.get('children').then(function(children){
                         children.reloadLinks();
                         });*/
                    });
                }
            }
        });
    };
});
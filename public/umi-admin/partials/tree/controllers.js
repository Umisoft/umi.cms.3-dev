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
                var properties = ['displayName', 'order', 'active', 'childCount', 'type', 'locked'];
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

            /**
             * Активный контекст
             * @property activeContext
             */
            activeContext: function(){
                return this.get('controllers.context.model');
            }.property('controllers.context.model'),

            /**
             * Индикатор процесса загрузки при частичной перезагрузке элементов дерева
             * @property isLoading
             */
            isLoading: false,

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
                    var collectionName = this.get('collectionName');
                    var ids = nextSibling || [];
                    var moveParams = {};
                    var resource;
                    var sibling;
                    var node;
                    var parent;
                    var oldParentId;
                    var store = self.get('store');
                    var models = store.all(collectionName);
                    var properties = self.get('properties');

                    self.send('showLoader');

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

                    resource = self.get('controllers.component.settings.actions.move.source');
                    return $.ajax({
                        'type': 'POST',
                        'url': resource,
                        'data': JSON.stringify(moveParams),
                        'dataType': 'json',
                        'contentType': 'application/json',
                        global: false,
                        success:function(){
                            ids.push(id);
                            var parentsUpdateRelation = {};

                            if(parentId !== oldParentId){
                                if(parentId && parentId !== 'root'){
                                    ids.push(parentId);
                                    parentsUpdateRelation.currentParent = parentId;
                                }
                                if(oldParentId && oldParentId !== 'root'){
                                    ids.push(oldParentId);
                                    parentsUpdateRelation.oldParent = oldParentId;
                                }
                            }

                            var promise;
                            var requestParams = {};
                            requestParams.fields = properties.join(',');
                            requestParams['filters[id]'] = 'equals(' + ids.join(',') + ')';

                            promise = store.updateCollection(collectionName, requestParams);


                            return promise.then(
                                function(updatedObjects){
                                    var parent;

                                    if(parentId !== 'root'){
                                        node = updatedObjects.findBy('id', id);
                                        store.find(collectionName, parentId).then(function(parent){
                                            node.set('parent', parent);
                                        });
                                    }

                                    if(parentId !== oldParentId){
                                        for(var key in parentsUpdateRelation){
                                            if(parentsUpdateRelation.hasOwnProperty(key)){
                                                parent = models.findBy('id', parentsUpdateRelation[key]);
                                                parent.trigger('needReloadHasMany', (key === 'currentParent' ? 'add' : 'remove'), node);
                                            }
                                        }
                                        if(parentId !== oldParentId && (parentId === 'root' || oldParentId === 'root')){
                                            self.get('controllers.component').trigger('needReloadRootElements', (parentId === 'root' ? 'add' : 'remove'), node);
                                        }
                                    }

                                    self.send('hideLoader');
                                }
                            );

                        },
                        error:  function(error){
                            self.send('backgroundError', error);
                            self.send('hideLoader');
                        }
                    });
                },

                showLoader: function(){
                    this.set('isLoading', true);
                },

                hideLoader: function(){
                    this.set('isLoading', false);
                }
            }
        });

        UMI.TreeControlContextToolbarController = Ember.ObjectController.extend({
            needs: ['component'],

            componentNameBinding: 'controllers.component.name'
        });
    };
});
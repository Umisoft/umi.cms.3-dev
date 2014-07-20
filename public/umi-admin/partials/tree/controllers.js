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
                var properties = ['displayName', 'order', 'active', 'childCount', 'children', 'parent'];
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
                    hasChildren: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    typeKey: collectionName,
                    childCount: function(){
                        return true;
                    }.property('children.length'),
                    children: null,
                    updateChildren: function(id, parentId){
                        var objectContext = this;
                        var collectionName = self.get('collectionName');
                        var object = self.store.find(collectionName, id);
                        object.then(function(object){
                            objectContext.get('children.content').then(function(children){
                                if(parentId === 'root'){
                                    children.pushObject(object);
                                } else{
                                    children.removeObject(object);
                                }
                            });
                        });
                    }
                });
                var root = Root.create({});
                return [root];// Намеренно возвращается значение в виде массива, так как шаблон ожидает именно такой формат
            }.property('root.childCount', 'model'),

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
    };
});
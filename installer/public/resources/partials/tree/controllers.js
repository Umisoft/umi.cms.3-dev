define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
            needs: ['component', 'context'],

            objectProperties: function(){
                var objectProperties = ['displayName', 'order', 'active', 'childCount', 'children', 'parent'] ;
                var collectionName = this.get('controllers.component.collectionName');
                var model = this.get('store').modelFor(collectionName);
                var modelFields = Ember.get(model, 'fields');
                modelFields = modelFields.keys.list;
                for(var i = 0; i < objectProperties.length; i++){
                    if(!modelFields.contains(objectProperties[i])){
                        objectProperties.splice(i, 1);
                        --i;
                    }
                }
                return objectProperties;
            }.property('model'),

            expandedBranches: [],

            clearExpanded: function(){
                this.set('expandedBranches', []);
            }.observes('controllers.component.collectionName'),

            activeContextChange: function(){
                var expandedBranches = this.get('expandedBranches');
                var activeContext = this.get('activeContext');
                if(activeContext){
                    var mpath = [];
                    if(activeContext.get('id') !== 'root' && activeContext.get('mpath')){
                        mpath = activeContext.get('mpath').without(parseFloat(activeContext.get('id'))) || [];
                    }
                    mpath.push('root');
                    this.set('expandedBranches', expandedBranches.concat(mpath).uniq());
                }
            }.observes('activeContext').on('init'),

            /**
             Возвращает корневой элемент
             @property root
             @type Object
             @return
             */
            root: function(){
                var collectionName = this.get('controllers.component.collectionName');
                var sideBarControl = this.get('controllers.component.sideBarControl');
                if(!sideBarControl){
                    return;
                }
                var self = this;
                var Root = Ember.Object.extend({
                    displayName: Ember.typeOf(sideBarControl.params) === 'object' ? sideBarControl.params.rootNodeName : '',
                    root: true,
                    hasChildren: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    typeKey: collectionName,
                    childCount: function(){
                        return this.get('children.length');
                    }.property('children.length'),
                    children: function(){
                        var children;
                        var objectProperties;
                        try{
                            if(!collectionName){
                                throw new Error('Collection name is not defined.');
                            }
                            objectProperties = self.get('objectProperties').join(',');
                            var nodes = self.store.updateCollection(collectionName, {'filters[parent]': 'null()', 'fields': objectProperties});
                            children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                                content: nodes,
                                sortProperties: ['order', 'id'],
                                sortAscending: true
                            });
                        } catch(error){
                            var errorObject = {
                                'statusText': error.name,
                                'message': error.message,
                                'stack': error.stack
                            };
                            Ember.run.next(self, function(){
                                this.send('templateLogs', errorObject, 'component');
                            });
                        }
                        return children;
                    }.property(),
                    updateChildren: function(id, parentId){
                        var objectContext = this;
                        var collectionName = self.get('controllers.component.collectionName');
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

            rootChildren: null,
            /**
             Активный контекст
             */
            activeContextBinding: 'controllers.context.model',

            contextToolbar: function(){
                var sideBarControl = this.get('controllers.component.sideBarControl');
                if(sideBarControl && sideBarControl.get('contextToolbar')){
                    return sideBarControl.get('contextToolbar');
                }
            }.property('controllers.component.sideBarControl.contextToolbar'),

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
                    var type = this.get('controllers.component.collectionName');
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

                                //

                            });
                        },
                        function(error){
                            self.send('backgroundError', error);
                        }
                    );
                },

                sendActionForBehaviour: function(behaviour, object){
                    this.send(behaviour.name, object, behaviour);
                }
            }
        });

        UMI.TreeItemController = Ember.ObjectController.extend({
            objectBinding: 'content',

            objectProperties: function(){
                var object = this.get('model');
                var objectProperties = ['displayName', 'order', 'active', 'childCount', 'children', 'parent'] ;
                var collectionName = object.get('typeKey') || object.constructor.typeKey;
                var model = this.get('store').modelFor(collectionName);
                var modelFields = Ember.get(model, 'fields');
                modelFields = modelFields.keys.list;
                for(var i = 0; i < objectProperties.length; i++){
                    if(!modelFields.contains(objectProperties[i])){
                        objectProperties.splice(i, 1);
                        --i;
                    }
                }
                return objectProperties;
            }.property('content'),

            getChildren: function(){
                var model = this.get('model');
                var collectionName = model.get('typeKey') || model.constructor.typeKey;
                var promise;

                var fields = this.get('objectProperties');
                fields = fields.join(',');
                if(model.get('id') === 'root'){
                    promise = this.get('children');
                } else{
                    promise = this.store.updateCollection(collectionName, {'filters[parent]': this.get('model.id'), 'fields': fields});
                }
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: promise,
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
            },

            sortedChildren: function(){
                return this.getChildren();
            }.property('didUpdate'),

            needs: 'treeControl',

            init: function(){
                var self = this;
                if('needReloadHasMany' in this.get('content')){
                    this.get('content').on('needReloadHasMany', function(){
                        self.get('children').then(function(children){
                            children.reloadLinks();
                        });
                    });
                }
            }
        });
    };
});
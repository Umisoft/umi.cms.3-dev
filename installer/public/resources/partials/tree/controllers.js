define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
            controlName: function(){
                return this.get('controllers.component.collectionName');
            }.property('controllers.component.collectionName'),
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
                    displayName: sideBarControl.displayName,
                    root: true,
                    hasChildren: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    childCount: function(){
                        return this.get('children.length');
                    }.property('children.length'),
                    children: function(){
                        var children;
                        try{
                            if(!collectionName){
                                throw new Error('Collection name is not defined.');
                            }
                            var nodes = self.store.find(collectionName, {'filters[parent]': 'null()'/*, 'fields': 'displayName,order,active,childCount,children,parent'*/});
                            var children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
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
                            Ember.run.next(function(){
                                self.send('templateLogs', errorObject, 'component');
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
            }.property('root.childCount', 'controllers.component.sideBarControl'),
            rootChildren: null,
            filters: function(){
                var filters = this.get('controllers.component.sideBarControl.filters');
                return filters;
            }.property('controllers.component.sideBarControl'),
            activeFilters: function(){
                return this.get('filters').filterBy('isActive', true);
            }.property('filters.@each.isActive'),
            /**
             Активный контекст
             */
            activeContextBinding: 'controllers.context.model.object',
            needs: ['component', 'context'],
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
                            });
                        },
                        function(error){
                            self.send('backgroundError', error);
                        }
                    );
                },

                toggleFastAction: function(action){
                    var selectAction;
                    var controlName = this.get('controlName');
                    if(!this.get('selectAction') || this.get('selectAction').type !== action.type){
                        selectAction = action;
                    } else{
                        selectAction = null;
                    }
                    this.set('selectAction', selectAction);
                    UMI.Utils.LS.set('treeControls.' + controlName + '.contextAction', selectAction);
                },

                sendAction: function(action, object){
                    this.send(action.type, object);
                }
            },
            selectAction: function(){
                var controlName = this.get('controlName');
                return UMI.Utils.LS.get('treeControls.' + controlName + '.contextAction');
            }.property('controlName'),
            selectActionIcon: function(){
                var iconType;
                if(this.get('selectAction')){
                    switch(this.get('selectAction.type')){
                        case 'getCreateForm':
                            iconType = 'add';
                            break;
                        case 'getEditForm':
                            iconType = 'edit';
                            break;
                        case 'switchActivity':
                            iconType = 'pause';
                            break;
                    }
                    return 'icon-' + iconType;
                }

            }.property('selectAction'),
            actionList: function(){
                return this.get('controllers.component.sideBarControl.toolbar');
            }.property()
        });

        UMI.TreeItemController = Ember.ObjectController.extend({
            sortedChildren: function(){
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: this.get('children'),
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
            }.property('children'),
            visible: function(){
                var visible = true;
                var filters = this.get('filters');
                var model = this.get('model');
                var i;
                for(i = 0; i < filters.length; i++){
                    if(!filters[i].allow.contains(model.get(filters[i].fieldName))){
                        visible = false;
                    }
                }
                return visible;
            }.property('model', 'filters'),
            filters: Ember.computed.alias("controllers.treeControl.activeFilters"),
            needs: 'treeControl',
            isExpanded: function(){
                var activeContext = this.get('controllers.treeControl.activeContext');
                if(this.get('id') === 'root'){
                    return true;
                } else{
                    if(!activeContext || activeContext.get('id') === 'root'){
                        return false;
                    }
                    var contains = activeContext.get('mpath').contains(parseFloat(this.get('id')));
                    if(contains && activeContext.get('id') !== this.get('id')){
                        return true;
                    } else{
                        return false;
                    }
                }
            }.property('root'),
            actions: {
                expanded: function(){
                    this.set('isExpanded', !this.get('isExpanded'));
                }
            }
        });
    };
});
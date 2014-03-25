define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
            /**
             Query params для текущего роута 'Component'
             @property routeParams
             @type Object
             @default null
             */
            routeParams: null,
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
                 var Root = Ember.Object.extend({
                    displayName: sideBarControl.displayName,
                    root: true,
                    hasChildren: true,
                    id: 'root',
                    active: true,
                    type: 'base',
                    childCount: function(){
                        return this.get('children.length');
                    }.property('children.length')
                });
                var nodes = this.store.find(collectionName, {'filters[parent]': 'null()'/*, 'fields': 'displayName,order,active,childCount,children,parent'*/});
                var children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: nodes,
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
                var root = Root.create({});
                root.set('children', children);
                return [root];// Намеренно возвращается значение в виде массива, так как шаблон ожидает именно такой формат
            }.property('root.childCount', 'controllers.component.sideBarControl'),
            filters: function(){
                return [
                    {
                        "title": "Показывать неактивные страницы",
                        "property": "active",
                        "checked": "checked",
                        "name": "inactive"
                    }
                ];
            }.property(),
            hideFilters: {},
            /**
             Активный контекст
             */
            activeContextBinding: 'controllers.context.model.object',
            needs: ['component', 'context'],
            actions: {
                filter: function(name, element){

                },
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
                    var type = this.get('collections')[0].type;//TODO: а как же несколько коллекций?
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

                    resource = [window.UmiSettings.baseApiURL];
                    resource.push(self.get('routeParams').module.module);
                    resource.push(self.get('routeParams').component.component);
                    resource.push('collection');
                    resource.push(type);
                    resource.push('move');
                    resource = resource.join('/');
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

                                if(parentId === 'root' || oldParentId === 'root'){
                                    //TODO: Зачем загружать заново элементы которые уже есть?
                                    self.incrementProperty('root.childCount');
                                }
                            });
                        }
                    );
                }
            }
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
                var counter = 0;
                var filters = this.get('filters');
                var filter;
                var model = this.get('model');
                for(filter in filters){
                    if(model.get(filter) === filters[filter]){
                        ++counter;
                    }
                }
                return (counter ? false : true);
            }.property('model', 'filters'),
            filters: Ember.computed.alias("controllers.treeControl.hideFilters"),
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
                },
                fastAction: function(){
                    this.transitionToRoute('context', 'add');
                }
            }
        });
    };
});
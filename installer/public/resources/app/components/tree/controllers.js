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
             Коллекция объектов
             @property collection
             @type Object
             @default null
             */
            collection: null,
            /**
             Возвращает корневой элемент
             @property root
             @type Object
             @return
             */
            root: function(){
                var root = Ember.Object.create(this.get('collection'));
                root.set('root', true);
                root.set('hasChildren', true);
                root.set('id', 'root');
                var nodes = this.store.find(root.get('type'), {'filters[parent]': 'null()', 'fields': 'displayName,order,childCount,children,parent'});
                var children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: nodes,
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
                root.set('children', children);
                return root;
            }.property('collection'),
            /**
             * При совпадении значения свойства в данном
             * */
            filterProperty: {
                type: 'component'
            },
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
                    var type = this.get('collection').type;
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
                    oldParentId = node.get('parent.id');

                    parent = models.findBy('id', parentId);
                    if(parent){
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
                    resource.push(type);
                    resource.push('move');
                    resource = resource.join('/');

                    $.post(resource, moveParams).then(
                        function(result, content, header){
                            ids.push(id);
                            var parentsUpdateRelation = [];
                            if(parentId !== oldParentId){
                                if(parentId){
                                    ids.push(parentId);
                                    parentsUpdateRelation.push(parentId);
                                }
                                if(oldParentId){
                                    ids.push(oldParentId);
                                    parentsUpdateRelation.push(oldParentId);
                                }
                            }
                            self.store.findByIds(type, ids).then(function(nodes){
                                /*var parent;
                                for(var i = 0; i < ids.length; i++){
                                    var item = models.findBy('id', ids[i]);
                                    self.store.unloadRecord(item);
                                }*/
                                //self.store.findByIds(type, ids);
                                nodes.invoke('reload');
                            });
                        }
                    );
                }
            }
        });

        UMI.TreeItemController = Ember.ObjectController.extend({
            isLoaded: false,
            actions: {
                expanded: function(){
                    this.set('isExpanded', !this.get('isExpanded'));
                }
            },
            isExpanded: function(){
                return !!this.get('root');
            }.property(),
            sortedChildren: function(){
                console.log(this);
                return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: this.get('children'),
                    sortProperties: ['order', 'id'],
                    sortAscending: true
                });
            }.property('children', 'childCount'),
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
            filters: Ember.computed.alias("controllers.treeControl.filterProperty"),
            needs: 'treeControl'
        });
    };
});
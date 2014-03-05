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
            collection: null,
            root: function(){
                var root = Ember.Object.create(this.get('collection'));
                root.set('root', true);
                root.set('hasChildren', true);
                root.set('id', 'root');
                var nodes = this.store.find(root.get('type'), {'parent': null});
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
                 @param Array Массив id элементов которые в результате перемещения поменяли свой индекс
                 */
                updateSortOrder: function(id, parentId, siblingId, ids){
                    var self = this;
                    ids = ids || [];
                    var type = this.get('collection').type;
                    var needNodes = [];
                    needNodes.push(id);
                    if(siblingId){
                        needNodes.push(siblingId);
                    }
                    var parentId = node.get('parent.id');
                    if(parentId){
                        needNodes.push(parentId);
                    }
                    self.store.find(type, needNodes).then(function(models){
                        var node = models[0];
                        var parent = models[1];
                        var sibling = models[2];

                        var moveParams = {
                            'object': {'id': node.get('id'), 'version': node.get('version')},
                            //'branch': node.get('parent') ? node.get('parent').toJSON() : null,
                            'sibling': models[1] ? {'id': models[1].get('id'), 'version': models[1].get('version')} : null
                        };
                        console.log();
                        var resource = [window.UmiSettings.baseApiURL];
                        resource.push(self.get('routeParams').module.module);
                        resource.push(self.get('routeParams').component.component);
                        resource.push(type);
                        resource.push('move');
                        resource = resource.join('/');

                        $.post(resource, moveParams).then(
                            function(){
                                var oldParentId = node.get('parent.id');

                                if(ids.indexOf(node.get('id')) === -1){
                                    ids.push(node.get('id'));
                                }

                                if(parentId !== oldParentId){
                                    if(parentId){
                                        ids.push(parentId);
                                    }
                                    if(oldParentId){
                                        ids.push(oldParentId);
                                    }
                                }
                                self.store.findByIds(type, ids).then(function(nodes){
                                    nodes.invoke('reload');
                                });
                            }
                        );
                    });
                }
            }
        });

        UMI.TreeItemController = Ember.ObjectController.extend({
            isLoaded: false,
            index: function(){
                return this.get('target').indexOf(this);
            }.property('target.[]'),
            actions: {
                expanded: function(){
                    this.set('isExpanded', !this.get('isExpanded'));
                }
            },
            isExpanded: function(){
                return !!this.get('root');
            }.property(),
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
            filters: Ember.computed.alias("controllers.treeControl.filterProperty"),
            needs: 'treeControl'
        });
    };
});
define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.TreeControlController = Ember.ObjectController.extend({
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
                 * Сохранение результата drag and drop
                 * @params
                 * */
                updateSortOrder: function(indexes, id, parentId){
                    var type = this.get('content.firstObject').constructor.typeKey;
                    var node = this.store.find(type, id);
                    var parent = this.store.find(type, parentId);
                    var self = this;
                    var oldParent;
                    var nodes = [];
                    var dirtyNodes;

                    Ember.RSVP.Promise.all([node, parent]).then(function(values){
                        node = values[0];
                        parent = values[1];
                        oldParent = node.get('parent');
                        /**В результате перемещения ноды находящиеся перед перемещаемым элементом должны изменить индекс*/
                        var listNodes = self.store.all(type);
                        for(var key in indexes){
                            if(indexes.hasOwnProperty(key)){
                                nodes.push(listNodes.findBy('id', key).set('index', indexes[key]));
                            }
                        }
                        // При перемещении ноды в другого родителя
                        // изменим связи belongsTo и hasMany для
                        // соответствующих нод
                        if(parent.get('id') !== oldParent.get('id')){
                            if(oldParent){
                                oldParent.get('children').removeObject(node);// удаляем связь hasMany
                            }
                            //console.log(parent);
                            node.set('parent', parent); // добавляем связь belongTo
                            if(parent){
                                parent.get('children').addObject(node); // добавляем связь hasMany
                            }
                        }
                        dirtyNodes = nodes.filterBy('isDirty', true);
                        //dirtyNodes.invoke('save');
                    });
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
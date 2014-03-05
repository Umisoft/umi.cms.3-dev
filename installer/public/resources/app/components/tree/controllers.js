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
                updateSortOrder: function(id, parentId, siblingId){
                    var type = this.get('collection').type;
                    var promises = [];
                    var moveParams = {
                        'object': id,
                        'branch': parentId,
                        'sibling': siblingId
                    };

                    var node = this.store.find(type, id);
                    promises.push(node);
                    console.log(this);
                    /*if(parentId){
                        var parent = this.store.find(type, parentId);
                        promises.push(parent);
                    }
                    $.post('/admin/api/news/rubric/newsRubric/move', moveParams).then(
                        function(){
                            Ember.RSVP.Promise.all(promises).then(function(values){
                                values[0].reload();
                                if(parentId){
                                    values[1].reload();
                                }
                            });
                        }
                    );*/
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
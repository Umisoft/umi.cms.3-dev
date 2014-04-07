define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.TableControlController = Ember.ObjectController.extend({
            limit: function(){

            }.property(),
            objects: function(){
                var self = this;
                var children = Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                    content: self.get('model.object.children'),
                    sortProperties: ['id'],
                    sortAscending: true
                });
                return children;
            }.property('model.object.children'),
            actions: {
                sortByProperty: function(propertyName){
                    this.get('objects').set('sortProperties', [propertyName]);
                    this.get('objects').toggleProperty('sortAscending');
                }
            }
        });
    };
});
define(['App', 'text!./layout.hbs'], function(UMI, layoutTpl){
    'use strict';

    UMI.MultiSelectView = Ember.View.extend({
        classNames: ['row', 'collapse', 'umi-multi-select'],
        template: Ember.Handlebars.compile(layoutTpl),
        isOpen: false,
        placeholder: '',
        collection: function(){
            var self = this;
            var store = self.get('controller.store');
            var property = this.get('meta.dataSource');
            var object = this.get('object');
            var collection;
            /*var getCollection = function(relation){
                collection = store.findAll(relation.type);
            };
            object.eachRelationship(function(name, relation){
                if(name === property){
                    getCollection(relation);
                }
            });*/
            collection = [
                Ember.Object.create({'id': 1, 'displayName': 'Спорт'}),
                Ember.Object.create({'id': 2, 'displayName': 'Олимпиада'}),
                Ember.Object.create({'id': 3, 'displayName': 'Каплан'})
            ];
            return collection;
        }.property(),
        selectedIds: [],
        selectedObjects: function(){
            var collection = this.get('collection');
            var selectedObjects = [];
            var selectedIds = this.get('selectedIds');

            collection.forEach(function(item){
                var id = item.get('id');
                if(selectedIds.contains(id)){
                    selectedObjects.push(item);
                }
            });

            return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                content: selectedObjects,
                sortProperties: ['displayName', 'id'],
                sortAscending: true
            });
        }.property('selectedIds.@each'),
        notSelectedObjects: function(){
            var collection = this.get('collection');
            var selectedObjects = [];
            var selectedIds = this.get('selectedIds');

            collection.forEach(function(item){
                var id = item.get('id');
                if(!selectedIds.contains(id)){
                    selectedObjects.push(item);
                }
            });

            return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
                content: selectedObjects,
                sortProperties: ['displayName', 'id'],
                sortAscending: true
            });
        }.property('selectedIds.@each', 'filterInFocus'),
        filter: null,
        opened: function(){
            var isOpen = this.get('isOpen');
            var self = this;
            if(isOpen){
                $('body').on('click.umi.multiSelect', function(event){
                    if(!$(event.target).closest('.umi-multi-select-list').length){
                        self.set('isOpen', false);
                    }
                });
                $('body').on('keypress.umi.multiSelect', function(event){
                    if(!$(event.target).closest('.umi-multi-select-list').length){
                        self.set('isOpen', false);
                    }
                });
            } else{
                $('body').off('.umi.multiSelect');
            }
        }.observes('isOpen'),
        actions: {
            toggleList: function(){
                this.set('filter', null);
                var isOpen = !this.get('isOpen');
                this.set('isOpen', isOpen);
            },
            select: function(id){
                this.get('selectedIds').pushObject(id);
                this.set('isOpen', false);
            },
            unSelect: function(id){
                this.get('selectedIds').removeObject(id);
            }
        },
        inputView: Ember.View.extend({
            tagName: 'input',
            classNames: ['umi-multi-select-input'],
            attributeBindings: ['parentView.placeholder:placeholder', 'value', 'autocomplete'],
            autocomplete: 'off',
            value: function(){
                var selectedObject = this.get('parentView.selectedObjects');
                var value;
                if(selectedObject.length){
                    value = '';
                } else{
                    value = '';
                }
                return value;
            }.property('parentView.selectedObjects'),
            keyUp: function(event){
                var val = this.$().val();
                if(!val){
                    return;
                }
                var pattern = new RegExp("^" + val);
                var notSelectedObjects = this.get('parentView.notSelectedObjects');
                var filteredObjects = [];
                filteredObjects = notSelectedObjects.filter(function(item){
                    if(pattern.test(item.get('displayName'))){
                        return true;
                    }
                });
                this.get('parentView').set('filter', {result: filteredObjects});
                this.get('parentView').set('isOpen', true);
            },
            keyDown: function(event){
                event.stopPropagation();
                var key;
                switch(event.keyCode){
                    case 38:
                        key = 'Up';
                        break;
                    case 40:
                        key = 'Down';
                        break;
                    case 13:
                        key = 'Enter';
                        break;
                    case 27:
                        key = 'Escape';
                        break;
                }
                switch(key){
                    case 'Down':
                }
            },
            blur: function(){
                this.$()[0].value = '';
            }
        }),
        init: function(){
            this._super();
            this.get('selectedIds').pushObject(1);
        }
    });
});
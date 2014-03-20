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
                Ember.Object.create({'id': 3, 'displayName': 'Окервиль'}),
                Ember.Object.create({'id': 4, 'displayName': 'Спагетти'}),
                Ember.Object.create({'id': 5, 'displayName': 'Спорт и не только'}),
                Ember.Object.create({'id': 6, 'displayName': 'Хоккей'}),
                Ember.Object.create({'id': 7, 'displayName': 'Футбол'}),
                Ember.Object.create({'id': 8, 'displayName': 'Тенис'}),
                Ember.Object.create({'id': 9, 'displayName': 'Релиз UMI.CMS 3'})
            ];
            return collection;
        }.property(),
        selectedIds: [],
        filterIds: [],
        filterOn: false,
        inputInFocus: false,
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
            return selectedObjects;
        }.property('selectedIds.@each'),
        notSelectedObjects: function(){
            var collection = this.get('collection');
            var notSelectedObjects = [];
            var ids;
            if(this.get('filterOn')){
                ids = this.get('filterIds');
                collection.forEach(function(item){
                    var id = item.get('id');
                    if(ids.contains(id)){
                        notSelectedObjects.push(item);
                    }
                });
            } else{
                ids = this.get('selectedIds');
                collection.forEach(function(item){
                    var id = item.get('id');
                    if(!ids.contains(id)){
                        notSelectedObjects.push(item);
                    }
                });
            }
            return notSelectedObjects;
        }.property('selectedIds.@each', 'filterIds'),
        opened: function(){
            var isOpen = this.get('isOpen');
            var self = this;
            if(isOpen){
                this.set('inputInFocus', true);
                $('body').on('click.umi.multiSelect', function(event){
                    if(!$(event.target).closest('.umi-multi-select-list').length || !$(event.target).hasClass('umi-multi-select-input')){
                        self.set('isOpen', false);
                    }
                });
            } else{
                $('body').off('.umi.multiSelect');
                this.set('inputInFocus', false);
                this.get('notSelectedObjects').setEach('hover', false);
            }
        }.observes('isOpen'),
        actions: {
            toggleList: function(){
                this.set('filterIds', []);
                this.set('filterOn', null);
                var isOpen = !this.get('isOpen');
                this.set('isOpen', isOpen);
            },
            select: function(id){
                this.get('selectedIds').pushObject(id);
               // this.set('isOpen', false);
            },
            unSelect: function(id){
                this.get('selectedIds').removeObject(id);
            },
            markHover: function(key){
                var collection = this.get('notSelectedObjects');
                var hoverObject = collection.findBy('hover', true);
                var index = 0;
                if(hoverObject){
                    hoverObject.set('hover', false);
                    index = collection.indexOf(hoverObject);
                    if(key === 'Down' && index < collection.length - 1){
                        ++index;
                    } else if(key === 'Up' && index){
                        --index;
                    }
                }
                collection.objectAt(index).set('hover', true);
            }
        },
        inputView: Ember.View.extend({
            tagName: 'input',
            classNames: ['umi-multi-select-input'],
            attributeBindings: ['parentView.placeholder:placeholder', 'value', 'autocomplete'],
            toggleFocus: function(){
                if(this.get('parentView.inputInFocus')){
                    this.$().focus();
                } else{
                    this.$().blur();
                }
            }.observes('parentView.inputInFocus'),
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
            doubleClick: function(){
                this.get('parentView').set('isOpen', true);
            },
            keyUp: function(event){
                var val = this.$().val();
                if(!val){
                    return;
                }
                var parentView = this.get('parentView');
                parentView.set('filterOn', true);
                var pattern = new RegExp("^" + val);// TODO: off math case
                var collection = parentView.get('collection');
                var filterIds = [];
                var selectedIds = parentView.get('selectedIds');
                collection.forEach(function(item){
                    if(pattern.test(item.get('displayName')) && !selectedIds.contains(item.get('id'))){
                        filterIds.push(item.get('id'));
                    }
                });
                parentView.set('filterIds', filterIds);
                parentView.set('isOpen', true);
            },
            keyDown: function(event){
                event.stopPropagation();
                var key;
                var parentView = this.get('parentView');
                //TODO: вынести маппинг кнопок в метод UMI.Utils
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
                    case 'Down': case 'Up':
                        parentView.send('markHover', key);
                        break;
                    case 'Enter':
                        parentView.send('selectHover');
                        break;
                    case 'Escape':
                        parentView.set('isOpen', false);
                        break;
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
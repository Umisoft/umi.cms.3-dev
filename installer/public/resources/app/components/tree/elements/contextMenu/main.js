define(
    ['App', 'text!./contextMenu.hbs'],
    function(UMI, ContextMenuTpl){
    "use strict";

    UMI.TreeControlContextMenuController = Ember.ObjectController.extend({
        activeGroup: null,
        fastAction: null,
        actionList: function(){
            var actions = [
                {
                    name: 'add',
                    list: [
                        {name: 'addNews', displayName: 'Добавить новость', type: 'query'},
                        {name: 'addRubric', displayName: 'Добавить рубрику', type: 'query'}
                    ]
                },
                {
                    name: 'pause',
                    list: [
                        {name: 'unActive', displayName: 'Снять активность', group: 'pause', type: "modify"}
                    ]
                }
            ];
            this.set('activeGroup', actions[0]);
            return actions;
        }.property(),
        fastActionIcon: function(){
            var actionList = this.get('actionList');
            var fastAction = this.get('fastAction');
            var groupName;
            actionList.forEach(function(group){
                if(group.list.contains(fastAction)){
                    groupName = group.name;
                }
            });
            return 'icon-' + groupName;
        }.property('fastAction'),
        actions: {
            toggleFastAction: function(action){
                var fastAction;
                if(!this.get('fastAction') || this.get('fastAction').name !== action.name){
                    fastAction = action;
                } else{
                    fastAction = null;
                }
                this.set('fastAction', fastAction);
            },
            setActiveGroup: function(action){
                this.set('activeGroup', action);
            },
            fastAction: function(action){
                if(action.type === 'query'){

                } else if(action.type === 'modify'){

                }
            }
        }
    });
    UMI.treeControlContextMenu = UMI.TreeControlContextMenuController.create({});

    UMI.TreeControlContextMenuView = Ember.View.extend({
        tagName: 'ul',
        classNames: ['button-group', 'umi-tree-context-menu', 'right'],
        layout: Ember.Handlebars.compile(ContextMenuTpl),
        isOpen: false,
        setParentIsOpen: function(){
            this.get('parentView').set('contextMenuIsOpen', this.get('isOpen'));
        }.observes('isOpen'),
        actions: {
            open: function(){
                var self = this;
                var el = this.$();
                this.toggleProperty('isOpen');
                if(this.get('isOpen')){
                    $('body').on('click.umi.tree.contextMenu', function(event){
                        var targetElement = $(event.target).closest('.umi-tree-context-menu');
                        if(!targetElement.length || targetElement[0].getAttribute('id') !== el[0].getAttribute('id')){
                            $('body').off('umi.tree.contextMenu.click');
                            self.set('isOpen', false);
                        }
                    });
                }
            }
        },
        controller: UMI.treeControlContextMenu,
        groupView: Ember.View.extend({
            tagName: 'li',
            classNameBindings: ['isActive:active'],
            isActive: function(){
                return this.get('action').name === this.get('parentView.controller.activeGroup').name;
             }.property('parentView.controller.activeGroup')
        }),
        itemView: Ember.View.extend({
            tagName: 'li',
            isFastAction: function(){
                var fastAction = this.get('parentView.controller.fastAction');
                return fastAction ? this.get('action').name === fastAction.name : false;
            }.property('parentView.controller.fastAction')
        })
    });
});
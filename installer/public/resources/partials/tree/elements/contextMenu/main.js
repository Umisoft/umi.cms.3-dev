define(
    ['App', 'text!./contextMenu.hbs'],
    function(UMI, ContextMenuTpl){
    "use strict";

    var ContextMenuController = Ember.Controller.extend({
        selectAction: function(){
            return UMI.Utils.LS.get('treeControls.contextAction');
        }.property(),
        selectActionIcon: function(){
            return 'icon-' + this.get('selectAction.icon');
        }.property('selectAction'),
        actionList: function(){
            var actions = [
                {name: 'addNews', displayName: 'Добавить новость', type: 'query',  icon: "add"},
                {name: 'unActive', displayName: 'Снять активность', type: "modify", icon: "pause"}
            ];
            return actions;
        }.property(),
        actions: {
            toggleFastAction: function(action){
                var selectAction;
                if(!this.get('selectAction') || this.get('selectAction').name !== action.name){
                    selectAction = action;
                } else{
                    selectAction = null;
                }
                this.set('selectAction', selectAction);
                UMI.Utils.LS.set('treeControls.contextAction', selectAction);
            },
            selectAction: function(action){
                if(action.type === 'query'){

                } else if(action.type === 'modify'){

                }
            }
        }
    });
    var contextMenuController = ContextMenuController.create({});

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
                            $('body').off('.umi.tree.contextMenu.click');
                            self.set('isOpen', false);
                        }
                    });
                }
            }
        },
        controller: contextMenuController,
        itemView: Ember.View.extend({
            tagName: 'li',
            isFastAction: function(){
                var selectAction = this.get('parentView.controller.selectAction');
                return selectAction ? this.get('action').name === selectAction.name : false;
            }.property('parentView.controller.selectAction')
        })
    });
});
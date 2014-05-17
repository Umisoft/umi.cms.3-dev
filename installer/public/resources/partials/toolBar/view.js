define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ToolBarController = Ember.ObjectController.extend({
//            actionList: function(){
//                return this.get('controllers.component.sideBarControl.toolbar');
//            }.property()
            needs: ['component']
        });

        UMI.ToolBarView = Ember.View.extend({
            templateName: 'toolBar',

            didInsertElement: function(){
                $(document).on('click', '.umi-toolbar-create-icon', function(){
                    $('.umi-toolbar-create-list').toggle();
                });
            },

            groupCrudView: Ember.View.extend({
                actions: {
                    pauseGroup: function(){
                        console.log('pauseGroup');
                    },

                    deleteGroup: function(){
                        console.log('deleteGroup');
                    }
                }
            }),

            itemView: Ember.View.extend({
                isFastAction: function(){
                    var selectAction = this.get('controller.controllers.treeControl.selectAction');
                    return selectAction ? this.get('action').type === selectAction.type : false;
                }.property('controller.controllers.treeControl.selectAction')
            })
        });
    }
});
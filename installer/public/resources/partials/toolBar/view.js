define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ToolBarController = Ember.ObjectController.extend({
            needs: ['component']
        });

        UMI.ToolBarView = Ember.View.extend({
            templateName: 'toolBar',

            didInsertElement: function(){
                $(document).on('click', '.umi-toolbar-create-icon', function(){
                    $('.umi-toolbar-create-list').toggle();
                });
            },

            createMenu: function(){

            },

            actions: {
                objectCreate: function(){
//                    console.log('collectionName', this.get('controller.collectionName')); //newsRubric
//                    console.log('selectedContext', this.get('controller.selectedContext')); //root
//
//                    console.log('settings', this.get('controller.settings'));
//                    console.log('contentControls', this.get('controller.contentControls')[1].toolbar);
//                    console.log('sideBarControl', this.get('controller.sideBarControl'));

                    //                        this controllers.treeControl.model
                }
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
            })
        });
    }
});
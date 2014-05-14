define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.ToolBarView = Ember.View.extend({
            templateName: 'toolBar',

            actionList: function(){
                return this.get('controllers.component.sideBarControl.toolbar');
            }.property()
        });
    }
});
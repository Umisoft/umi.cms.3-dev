define(['App'], function(UMI){
    'use strict';

//    UMI.FileManagerController = Ember.ObjectController.extend({
//        needs: ['action'],
//        data: function(){
//            return this.get('controllers.action').get('model').get('action');
//        }.property()
//    });

    UMI.FileManagerView = Ember.View.extend({
        tagName: 'div',
        template: Ember.Handlebars.compile('<div id="elfinder"></div>'),
        didInsertElement: function(){
//            http://localhost/admin/api/files/manager/settings
            var action = '/connector';//this.controller.get('data');
            console.log(action);
            $('#elfinder').elfinder({url : action}).elfinder('instance');
        }
    });
});
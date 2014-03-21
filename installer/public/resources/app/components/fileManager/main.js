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
            var action = '/admin/api/files/manager/fileManager/connector';//this.controller.get('data');
            $('#elfinder').elfinder({
                url : '/admin/api/files/manager/connector',
                lang: 'ru'
            }).elfinder('instance');
        }
    });
});
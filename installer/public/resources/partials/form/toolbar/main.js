define(['App', 'text!./toolbar.hbs'], function(UMI, toolbarTpl){
    "use strict";

    return function(){

        UMI.FormToolbarController = Ember.ArrayController.extend({
            backupList: function(){
                var backupList;
                var object = this.get('parentController.object');
                var settings = this.get('parentController.settings');

                var currentVersion = {
                    objectId: object.get('id'),
                    date: object.get('updated'),
                    user: null,
                    id: 'current',
                    current: true,
                    isActive: true
                };

                var results = [currentVersion];
                var params = '?id=' + object.get('id');

                var promiseArray = DS.PromiseArray.create({
                    promise: $.get(settings.actions.getBackupList.source + params).then(function(data){
                        return results.concat(data.result.getBackupList.serviceBackup);
                    })
                });

                backupList = Ember.ArrayProxy.create({
                    content: promiseArray
                });
                return backupList;

            }.property('parentController.object'),

            actions: {
                applyBackup: function(backup){
                    if(backup.isActive){
                        return;
                    }
                    var self = this;
                    var object = this.get('parentController.model.object');
                    var list = self.get('backupList');
                    var setCurrent = function(){
                        list.setEach('isActive', false);
                        var current = list.findBy('id', backup.id);
                        Ember.set(current, 'isActive', true);
                    };
                    object.rollback();
                    if(backup.current){
                        setCurrent();
                    } else{
                        var params = '?id=' + backup.objectId + '&backupId=' + backup.id;
                        $.get(self.get('parentController.settings').actions.getBackup.source + params).then(function(data){
                            object.setProperties(data.result.getBackup);
                            setCurrent();
                        });
                    }
                }
            }
        });

        UMI.FormToolbarItemController = Ember.ObjectController.extend({
            isApply: function(){
                return this.get('content.type') === 'apply';
            }.property(),

            isSwitchActivity: function(){
                return this.get('content.type') === 'switchActivity';
            }.property(),

            isBackups: function(){
                return this.get('content.type') === 'backupList';
            }.property(),

            isTrash: function(){
                return this.get('content.type') === 'trash';
            }.property()
        });

        UMI.FormToolbarView = Ember.View.extend({
            layout: Ember.Handlebars.compile(toolbarTpl),
            tagName: 'ul',
            classNames: ['button-group', 'umi-form-control-buttons']
        });
    };
});

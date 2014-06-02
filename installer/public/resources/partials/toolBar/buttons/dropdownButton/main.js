define(['App', 'text!./template.hbs', 'text!./backupList.hbs'],
    function(UMI, template, backupListTemplate){
        "use strict";

        return function(){
            UMI.DropdownButtonView = Ember.View.extend({
                template: Ember.Handlebars.compile(template),
                tagName: 'a',
                classNameBindings: 'meta.attributes.class',
                attributeBindings: ['title'],
                title: Ember.computed.alias('meta.attributes.title'),
                didInsertElement: function(){
                    this.$().click(function(){
                        $(this).find('.umi-toolbar-create-list').toggle();
                    });
                }
            });

            UMI.dropdownButtonBehaviour = Ember.Object.create({
                backupList: UMI.FormControlDropUpView.extend({
                    tagName: 'div',
                    template: Ember.Handlebars.compile(backupListTemplate),

                    getBackupList: function(){
                        var backupList;
                        var object = this.get('object');
                        var settings = this.get('controller.settings');

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
                    },

                    backupList: null,

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
                            if(backup.current){
                                object.rollback();
                                setCurrent();
                            } else{
                                var params = '?id=' + backup.objectId + '&backupId=' + backup.id;
                                $.get(self.get('parentController.settings').actions.getBackup.source + params).then(function(data){
                                    object.rollback();
                                    delete data.result.getBackup.version;
                                    delete data.result.getBackup.id;
                                    object.setProperties(data.result.getBackup);
                                    setCurrent();
                                });
                            }
                        }
                    },

                    didInsertElement: function(){// TODO: При уходе с формы это событие снова всплывает
                        var self = this;
                        self.set('backupList', self.getBackupList());
                        self.get('object').off('didUpdate');
                        self.get('object').on('didUpdate', function(){
                            self.set('backupList', self.getBackupList());
                        });
                    }
                })
            });
        };
    }
);
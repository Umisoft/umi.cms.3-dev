define(['App', 'text!./alert-box.hbs'], function(UMI, alertBoxTpl){
    'use strict';

    UMI.Notification = Ember.Object.extend({
        settings: {
            'type': 'secondary',
            'title': 'UMI.CMS',
            'text': '',
            'close': true,
            'duration': 3000
        },
        create: function(params){
            var settings = this.get('settings');
            if(params){
                for(var param in params){
                    if(params.hasOwnProperty(param)){
                        settings[param] = params[param];
                    }
                }
            }
            settings.id = UMI.notificationList.incrementProperty('notificationId');
            var data = UMI.notificationList.get('content');
            data.pushObject(Ember.Object.create(settings));
        }
    });

    UMI.notification = UMI.Notification.create();

    UMI.NotificationList = Ember.ArrayController.extend({
        content: [],
        sortProperties: ['id'],
        sortAscending: true,
        notificationId: 0,
        closeAll: false,
        itemCount: function(){
            if(this.get('content.length') > 1 && !this.get('closeAll')){
                this.set('closeAll', true);
                this.get('content').pushObject(
                    Ember.Object.create({
                        id: 'closeAll',
                        type: 'secondary',
                        text: 'Закрыть все'
                    })
                );
            }
            if(this.get('content.length') <= 2 && this.get('closeAll')){
                var object = this.get('content').findBy('id', 'closeAll');
                this.get('content').removeObject(object);
                this.set('closeAll', false);
            }
        }.observes('content.length')
    });

    UMI.notificationList = UMI.NotificationList.create();

    UMI.AlertBox = Ember.View.extend({
        classNames: ['alert-box'],
        classNameBindings: ['content.type'],
        layout: Ember.Handlebars.compile(alertBoxTpl),
        didInsertElement: function(){
            var duration = this.get('content.duration');
            if(duration){
                Ember.run.later(this, function(){
                    //this.$().slideDown();
                    var id = this.get('content.id');
                    var content = this.get('controller.content') || [];
                    var object = content.findBy('id', id);
                    content.removeObject(object);
                }, duration);
            }
        },
        actions: {
            close: function(){
                var content = this.get('controller.content');
                content.removeObject(this.get('content'));
            }
        }
    });

    UMI.NotificationListView = Ember.CollectionView.extend({
        tagName: 'div',
        classNames: ['umi-alert-wrapper'],
        itemViewClass: UMI.AlertBox,
        contentBinding: 'controller.content',
        controller: UMI.notificationList
    });

    UMI.NotificationView = Ember.ContainerView.extend({
        childViews: ['iconView', 'listView'],
        listView: UMI.NotificationListView,
        iconView: Ember.View.extend({
            active: false,
            layout: Ember.Handlebars.compile('<a {{action "showNotify" target="view"}} {{bind-attr class="view.active"}}><i class="icon white icon-mail"></i></a>'),
            actions: {
                showNotify: function(){
                    UMI.notification.create({
                        type: 'info',
                        text: 'UMI.CMS is ready to update',
                        duration: false
                    });
                }
            }
        }),
        cons: function(){
            if(this.get('listView.content.length')){
                this.get('iconView').set('active', true);
            } else{
                this.get('iconView').set('active', false);
            }
        }.observes('listView.content.length')
    });


});
define(['App'], function(UMI) {
    'use strict';

    UMI.Notification = Ember.Object.extend({
        settings: {
            'type': 'secondary',
            'title': 'UMI.CMS',
            'content': '',
            'close': true,
            'duration': 3000,
            'kind': 'default'
        },
        create: function(params) {
            var defaultSettings = this.get('settings');
            var settings = {};
            var param;
            for (param in defaultSettings) {
                if (defaultSettings.hasOwnProperty(param)) {
                    settings[param] = defaultSettings[param];
                }
            }

            if (params) {
                for (param in params) {
                    if (params.hasOwnProperty(param)) {
                        settings[param] = params[param];
                    }
                }
            }

            settings.id = UMI.notificationList.incrementProperty('notificationId');
            var data = UMI.notificationList.get('content');

            Ember.run.next(this, function() {
                data.pushObject(Ember.Object.create(settings));
            });
        },
        removeAll: function() {
            UMI.notificationList.set('content', []);
        },
        removeWithKind: function(kind) {
            var content = UMI.notificationList.get('content');
            content = content.filter(function(item) {
                if (Ember.get(item, 'kind') !== kind) {
                    return true;
                }
            });
            UMI.notificationList.set('content', content);
        }
    });

    UMI.notification = UMI.Notification.create({});

    UMI.NotificationList = Ember.ArrayController.extend({
        content: [],
        sortContent: function() {
            return this.get('content').sortBy('id');
        }.property('content.length'),
        notificationId: 0,
        closeAll: false,
        itemCount: function() {
            var content = this.get('content');
            if (content.get('length') > 1 && !this.get('closeAll')) {
                this.set('closeAll', true);
                content.pushObject(Ember.Object.create({
                    id: 'closeAll',
                    type: 'secondary',
                    kind: 'closeAll',
                    content: UMI.i18n.getTranslate('Close') + ' ' + (UMI.i18n.getTranslate('All') || '').toLowerCase()
                }));
            }
            if (content.get('length') <= 2 && this.get('closeAll')) {
                var object = content.findBy('id', 'closeAll');
                content.removeObject(object);
                this.set('closeAll', false);
            }
        }.observes('content.length')
    });

    UMI.notificationList = UMI.NotificationList.create({});

    UMI.AlertBox = Ember.View.extend({
        classNames: ['alert-box'],
        classNameBindings: ['content.type'],
        layoutName: 'partials/alert-box',
        didInsertElement: function() {
            var duration = this.get('content.duration');
            if (duration) {
                Ember.run.later(this, function() {
                    //this.$().slideDown();
                    var id = this.get('content.id');
                    var content = this.get('controller.content') || [];
                    var object = content.findBy('id', id);
                    content.removeObject(object);
                }, duration);
            }
        },
        actions: {
            close: function() {
                var content = this.get('controller.content');
                content.removeObject(this.get('content'));
            }
        }
    });

    UMI.AlertBoxCloseAll = Ember.View.extend({
        classNames: ['alert-box text-center alert-box-close-all'],
        classNameBindings: ['content.type'],
        layoutName: 'partials/alert-box/close-all',
        click: function() {
            UMI.notification.removeAll();
        }
    });

    UMI.NotificationListView = Ember.CollectionView.extend({
        tagName: 'div',
        classNames: ['umi-alert-wrapper'],
        createChildView: function(viewClass, attrs) {
            if (attrs.content.kind === 'closeAll') {
                viewClass = UMI.AlertBoxCloseAll;
            } else {
                viewClass = UMI.AlertBox;
            }
            return this._super(viewClass, attrs);
        },
        contentBinding: 'controller.sortContent',
        controller: UMI.notificationList
    });
});
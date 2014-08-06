define(['App'], function(UMI) {
        'use strict';

        UMI.SideMenuController = Ember.ObjectController.extend({
            needs: ['component'],
            objects: function() {
                return this.get('controllers.component.dataSource.objects');
            }.property('model')
        });

        UMI.SideMenuView = Ember.View.extend({
            layoutName: 'partials/sideMenu'
        });
    });
define(['App'], function (UMI) {
    'use strict';

    return function () {
        UMI.SiteListController = Ember.ArrayController.extend({
            content: function () {
                return this.store.find('site_list');
            }.property(),
            sortProperties: ['id'],
            sortAscending: true
        });

        UMI.SiteListView = Ember.View.extend({
            classNames: ['inblock']
        });
    };
});
define(['./app'], function (UMI) {
    'use strict';

    return function () {

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            classNameBindings: ['showAllVersion'],
            showAllVersion: false,
            didInsertElement: function () {
                var self = this;
                Ember.run.next(this, function () {
                    $(document).foundation();
                });
                $(document).on('keydown', function (e) {
                    if (e.altKey && e.which === 86) {
                        self.toggleProperty('showAllVersion');
                    }
                });
            }
        });


        UMI.ToggleVersionView = Ember.View.extend({
            classNames: ['has-version'],
            actions: {
                toggleVersion: function (version) {
                    var v = {};
                    v[version] = true;
                    this.set('version', v);
                }
            },
            version: {v1: true}
        });
    };
});
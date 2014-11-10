define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.TimeElementView = Ember.View.extend({
            templateName: 'partials/timeElement',

            classNames: ['row', 'collapse'],

            globalObject: window,

            localization: function() {
                var locale = this.get('globalObject.UmiSettings.locale');

                if (locale) {
                    $.timepicker.setDefaults($.timepicker.regional[locale.replace(/-.*/g, '')]);
                }
            }.observes('globalObject.UmiSettings').on('init'),

            didInsertElement: function() {
                var el = this.$();
                el.find('.icon-delete').click(function() {
                    el.find('input').val('');
                });

                this.$().find('input').timepicker({
                    timeFormat: 'HH:mm:ss'
                });
            },

            inputView: Ember.View.extend({
                template: function() {
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder name=meta.attributes.name}}');
                }.property()
            })
        });

        UMI.FormTimeElementMixin = Ember.Mixin.create(UMI.FormElementMixin, UMI.FormElementValidateMixin, {
            classNames: ['small-12', 'large-4'],

            elementView: UMI.TimeElementView.extend(UMI.FormElementValidateHandlerMixin, {})
        });
    };
});
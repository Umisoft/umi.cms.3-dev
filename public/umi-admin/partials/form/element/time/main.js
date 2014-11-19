define(['App'], function(UMI) {
    'use strict';


    return function() {
        UMI.FormTimeElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "timeElement" object=view.object meta=view.meta}}')
        });

        UMI.TimeElementView = Ember.View.extend(UMI.FormElementValidatable, {
            templateName: 'partials/timeElement',

            classNames: ['row', 'collapse'],

            focusOut: function() {
                this.checkValidate();
            },

            focusIn: function() {
                this.clearValidate();
            },

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
    };
});
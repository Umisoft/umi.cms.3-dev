define(['App'], function(UMI) {
    'use strict';

    return function() {

        UMI.FormDateTimeElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],
            
            template: Ember.Handlebars.compile('{{view "dateTimeElement" object=view.object meta=view.meta}}')
        });

        UMI.DateTimeElementView = Ember.View.extend({
            templateName: 'partials/dateTimeElement',

            classNames: ['row', 'collapse'],

            value: null,

            changeValue: function() {
                Ember.run.once(this, 'setValueForObject');
            }.observes('value'),

            setValueForObject: function() {
                var self = this;

                if (Ember.typeOf(self.get('object')) === 'instance') {
                    var value = self.get('value');
                    value = Ember.isNone(value) ? '' : value;
                    var valueInObject = self.get('object.' + self.get("meta.dataSource")) || '';
                    if (value) {
                        if (valueInObject) {
                            valueInObject = JSON.parse(valueInObject);
                        } else {
                            valueInObject = {date: null};
                        }
                        valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                        if (valueInObject.date !== value) {
                            var result = '';
                            if (value) {
                                result = {
                                    date: value,
                                    timezone_type: 3,
                                    timezone: "Europe/Moscow"
                                };
                                result = JSON.stringify(result);
                            }
                            self.get('object').set(self.get("meta.dataSource"), result);
                        }
                    } else {
                        if (valueInObject !== value) {
                            self.get('object').set(self.get("meta.dataSource"), '');
                        }
                    }
                }
            },

            setInputValue: function() {
                var self = this;
                var valueInObject = self.get('object.' + self.get("meta.dataSource"));
                var value = self.get('value');
                value = Ember.isNone(value) ? '' : value;
                if (valueInObject) {
                    valueInObject = JSON.parse(valueInObject);
                    valueInObject.date = Ember.isNone(valueInObject.date) ? '' : valueInObject.date;
                    if (valueInObject.date !== value) {
                        self.set('value', valueInObject.date);
                    }
                } else {
                    if (valueInObject !== value) {
                        self.set('value', '');
                    }
                }
            },

            globalObject: window,

            localization: function() {
                var locale = this.get('globalObject.UmiSettings.locale');
                var localeCustomFormat = locale.replace(/-.*/g, '');

                if (locale && $.datepicker.regional[locale] && $.timepicker.regional[localeCustomFormat]) {
                    $.datepicker.setDefaults($.datepicker.regional[locale]);
                    $.timepicker.setDefaults($.timepicker.regional[localeCustomFormat]);
                } else {
                    $.timepicker.setDefaults($.timepicker.regional['']);
                }
            }.observes('globalObject.UmiSettings').on('init'),

            init: function() {
                this._super();
                var value;
                var self = this;
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        value = self.get('object.' + self.get("meta.dataSource")) || '{}';
                        value = JSON.parse(value);
                        self.set("value", value.date || "");

                        self.addObserver('object.' + self.get('meta.dataSource'), function() {
                            Ember.run.once(self, 'setInputValue');
                        });
                    } else {
                        self.set("value", self.get('meta.value'));
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            didInsertElement: function() {
                this.$().find('input').datetimepicker({
                    timeFormat: 'hh:mm:ss',
                    dateFormat: 'dd.mm.yy'
                });
            },

            willDestroyElement: function() {
                var self = this;
                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.removeObserver('object.' + self.get('meta.dataSource'));
                }
            },

            actions: {
                clearValue: function() {
                    var self = this;
                    self.set('value', '');
                }
            }
        });
    };
});
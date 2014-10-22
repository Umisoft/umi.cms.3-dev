define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormDateElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "dateElement" object=view.object meta=view.meta}}')
        });

        UMI.DateElementView = Ember.View.extend({
            templateName: 'partials/dateElement',

            classNames: ['row', 'collapse'],

            didInsertElement: function() {
                this.$().find('input').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd.mm.yy'
                });
            },

            actions: {
                clearValue: function() {
                    var self = this;
                    var el = self.$();
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else {
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
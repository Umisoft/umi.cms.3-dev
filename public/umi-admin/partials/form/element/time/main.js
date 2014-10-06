define(['App'], function(UMI) {
    'use strict';


    return function() {
        UMI.FormTimeElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            template: Ember.Handlebars.compile('{{view "timeElement" object=view.object meta=view.meta}}')
        });

        UMI.TimeElementView = Ember.View.extend(UMI.InputValidate, {
            templateName: 'partials/timeElement',
            classNames: ['row', 'collapse'],

            didInsertElement: function() {
                var el = this.$();
                el.find('.icon-delete').click(function() {
                    el.find('input').val('');
                });

                this.$().find('input').timepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    timeFormat: 'HH:mm:ss',
                    currentText: 'Выставить текущее время'
                });
            },

            inputView: Ember.View.extend({
                template: function() {
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validatorType="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});
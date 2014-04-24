define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DatePickerComponent = Ember.Component.extend({
            tagName: 'div',
            classNames: ['umi-input-wrapper-date'],
            object: null,
            property: null,

            valueObject: function(){
                return this.get('object.' + this.get("property") + '.date');
            }.property('object', 'property'),

            changeValueObject: function(){
                var property = this.get('object.' + this.get("property"));
                property.date = this.get('valueObject');
                this.get('object').set(this.get('property'), property);
            }.observes('valueObject'),

            layout: Ember.Handlebars.compile('{{input type="text" class="umi-date" value=valueObject}}'),

            didInsertElement: function(){
                var element = this.$().children('.umi-date');
                element.jdPicker({
                    month_names: [
                        "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
                    ],
                    short_month_names: [],
                    short_day_names: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    date_format: "dd/mm/YYYY"
                });
            }
        });
    };
});
define(['App', 'text!./dateTimeElement.hbs'], function(UMI, dateTimeElement){
    'use strict';

    Ember.TEMPLATES['UMI/components/date-time-element'] = Ember.Handlebars.compile(dateTimeElement);

    return function(){
        UMI.DateTimeElementComponent = Ember.Component.extend({
            classNames: ['umi-element-date-time'],
            didInsertElement: function(){
                var dateElement = this.$().children('.umi-element-date');
                var timeElement = this.$().children('.umi-element-time');

                dateElement.jdPicker({
                    month_names: [
                        "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
                    ],
                    short_month_names: ["Янв", "Февр", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сент", "Окт", "Нояб", "Дек"],
                    short_day_names: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    date_format: "dd/mm/YYYY"
                });

                timeElement.timepicker();
            }
        });
    };
});
define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DatePickerComponent = Ember.Component.extend({
            tagName: 'div',
            classNames: ['umi-input-wrapper-date'],
            object: null,
            property: null,

            valueObject: function(){
                var dateProperty = this.get('object.' + this.get("property"));
                if(dateProperty){
                    dateProperty = JSON.parse(dateProperty).date;
                }
                return dateProperty;
            }.property('object', 'property'),

            changeValueObject: function(){
                var value = this.get('valueObject');
                var dateProperty = this.get('object.' + this.get("property"));
                if(value){
                    if(dateProperty){
                        dateProperty = JSON.parse(dateProperty);
                    } else{// TODO: http://youtrack.umicloud.ru/issue/cms-355
                       dateProperty = {
                            timezone_type: 3,
                            timezone: "Europe/Moscow"
                        };
                    }
                    dateProperty.date = value;
                    dateProperty = JSON.stringify(dateProperty);
                } else{
                    dateProperty = null;
                }
                this.get('object').set(this.get('property'), dateProperty);
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
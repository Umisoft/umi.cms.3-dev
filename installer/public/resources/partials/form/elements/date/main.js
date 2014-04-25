define(['App', 'text!./dateElement.hbs'], function(UMI, dateElement){
    'use strict';

    Ember.TEMPLATES['UMI/components/date-element'] = Ember.Handlebars.compile(dateElement);

    return function(){
        UMI.DateElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-date'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });
                this.$().find('input').jdPicker({
                    month_names: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                    short_month_names: ["Янв", "Февр", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сент", "Окт", "Нояб", "Дек"],
                    short_day_names: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    date_format: "dd mm YYYY"
                });

//                this.$().find('input').datepicker({
//                    changeMonth: true,
//                    changeYear: true,
//                    dateFormat: 'dd.mm.yy'
//                });
            }
        });
    };
});
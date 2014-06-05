define(['App', 'text!./dateTimeElement.hbs'], function(UMI, dateTimeElement){
    'use strict';

    Ember.TEMPLATES['UMI/date-time-element'] = Ember.Handlebars.compile(dateTimeElement);

    return function(){
        UMI.DateTimeElementView = Ember.View.extend({
            templateName: 'date-time-element',
            classNames: ['umi-element', 'umi-element-date-time'],

            windowW: null,
            windowWidth: function(){}.property(),

            dateTimeView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection"}}');
                }.property()
            }),

            didInsertElement: function(){
                this.$().find('input').datetimepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    secondText: 'Секунды',
                    currentText: 'Выставить текущее время',

                    timeFormat: 'hh:mm:ss',
                    dateFormat: 'yy-mm-dd'
                });
            }
        });
    };
});
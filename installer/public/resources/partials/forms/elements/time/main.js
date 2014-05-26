define(['App', 'text!./timeElement.hbs'], function(UMI, timeElement){
    'use strict';

    Ember.TEMPLATES['UMI/components/time-element'] = Ember.Handlebars.compile(timeElement);

    return function(){
        UMI.TimeElementComponent = Ember.Component.extend(UMI.InputValidate, {
            classNames: ['umi-element', 'umi-element-time'],

            didInsertElement: function(){
                var el = this.$();
                el.find('.icon-delete').click(function(){
                    el.find('input').val('');
                });

                this.$().find('input').timepicker({
                    hourText: 'Часы',
                    minuteText: 'Минуты',
                    currentText: 'Выставить текущее время'
                });

//                this.$().find('input').click(function(){
//                    var y = $(this).offset().left;
//                    var y = $(this).offset().top;
//                    $('body').append('<div class="umi-timepicker"></div>');
//                });
            },

            inputView: Ember.View.extend({
                template: function(){
                    var dataSource = this.get('parentView.meta.dataSource');
                    return Ember.Handlebars.compile('{{input type="text" value=object.' + dataSource + ' placeholder=meta.placeholder validator="collection" name=meta.attributes.name}}');
                }.property()
            })
        });
    };
});
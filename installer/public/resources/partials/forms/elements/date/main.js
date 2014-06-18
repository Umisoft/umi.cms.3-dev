define(['App', 'text!./dateElement.hbs'], function(UMI, dateElement){
    'use strict';

    return function(){
        UMI.DateElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(dateElement),

            classNames: ['umi-element', 'umi-element-date'],

            didInsertElement: function(){
                this.$().find('input').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd.mm.yy'
                });
            },

            actions: {
                clearValue: function(){
                    var self = this;
                    var el = self.$();
                    if(Ember.typeOf(self.get('object')) === 'instance'){
                        var dataSource = self.get('meta.dataSource');
                        self.get('object').set(dataSource, '');
                    } else{
                        el.find('input').val('');
                    }
                }
            }
        });
    };
});
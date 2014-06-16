define(['App', 'text!./dateElement.hbs'], function(UMI, dateElement){
    'use strict';

    return function(){
        UMI.DateElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(dateElement),

            classNames: ['umi-element', 'umi-element-date'],

            value: null,

            changeValue: function(){
                var self = this;

                if(Ember.typeOf(self.get('object')) === 'instance'){
                    var value = self.get('value');
                    var valueInObject = self.get('object.' + self.get("meta.dataSource")) || '';
                    if(!valueInObject.date){
                        valueInObject.date = "";
                    }
                    if(valueInObject.date !== value){
                        var result = "";
                        if(value){
                            result = {
                                date: value,
                                timezone_type: 3,
                                timezone: "Europe/Moscow"
                            };
                            result = JSON.stringify(result);
                        }
                        self.get('object').set(self.get("meta.dataSource"), result);
                    }
                }
            }.observes('value'),

            setInputValue: function(){
                var self = this;
                var valueInObject = self.get('object.' + self.get("meta.dataSource")) || '{}';
                valueInObject = JSON.parse(valueInObject);
                if(!valueInObject.date){
                    valueInObject.date = "";
                }
                if(valueInObject.date !== self.get('value')){
                    self.set('value', valueInObject);
                }
            },

            layout: Ember.Handlebars.compile('{{input type="text" class="umi-date" value=view.value}}'),

            init: function(){
                this._super();
                var value;
                var self = this;

                if(Ember.typeOf(self.get('object')) === 'instance'){
                    value = self.get('object.' + self.get("meta.dataSource"))  || '{}';
                    value = JSON.parse(value);
                    self.set("value", value.date || "");

                    self.addObserver('object.' + self.get('meta.dataSource'), function(){
                        Ember.run.once(self, 'setInputValue');
                    });
                } else{
                    self.set("value", self.get('meta.value'));
                }
            },

            didInsertElement: function(){
                this.$().find('input').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd.mm.yy'
                });
            }
        });
    };
});
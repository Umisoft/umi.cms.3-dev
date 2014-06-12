define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.CheckboxElementView = Ember.View.extend({
            template: function(){
                var self = this;
                var isChecked;
                if(Ember.typeOf(self.get('object')) === 'instance'){
                    isChecked = self.get('object.' + self.get('meta.dataSource'));
                } else{
                    isChecked = self.get('meta.value');
                }
                var name = self.get('meta.attributes.name');
                var inputId = self.get('inputId');
                var hiddenInput = '<input type="hidden" name="' + name + '" value="0" />';
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="1"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(hiddenInput + checkbox + label);
            }.property(),

            classNames: ['umi-element-checkbox'],

            setCheckboxValue: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    $el.find('input[type="checkbox"]')[0].checked = self.get('object.' + self.get('meta.dataSource'));
                }
            },

            didInsertElement: function(){
                this._super();
                var self = this;
                if(Ember.typeOf(this.get('object')) === 'instance'){
                    self.addObserver('object.' + self.get('meta.dataSource'), function(){
                        console.log('bibndd');
                        Ember.run.once(self, 'setCheckboxValue');
                    });
                }
            },

            willDestroyElement: function(){
                var self = this;
                self.$().off('change');
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            actions: {
                change: function(){
                    var self = this;
                    var $el = this.$();
                    var checkbox;
                    if(Ember.typeOf(this.get('object')) === 'instance'){
                        self.get('object').toggleProperty(self.get('meta.dataSource'));
                    } else{
                        checkbox = $el.find('input[type="checkbox"]')[0];
                        checkbox.checked = !checkbox.checked;
                    }
                }
            }
        });
    };
});
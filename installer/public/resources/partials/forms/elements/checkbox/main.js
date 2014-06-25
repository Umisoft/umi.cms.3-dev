define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.CheckboxElementView = Ember.View.extend({
            template: function(){
                var self = this;
                var isChecked;
                var object = self.get('object');
                var meta = self.get('meta');
                var name = Ember.get(meta, 'attributes.name');
                var value = Ember.get(meta, 'attributes.value');

                if(Ember.typeOf(object) === 'instance'){
                    isChecked = Ember.get(object, Ember.get(meta, 'dataSource'));
                } else{
                    isChecked = value;
                }

                var hiddenInput = '<input type="hidden" name="' + name + '" value="0" />';
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
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

            addObserverProperty: function(){
                var self = this;
                if(Ember.typeOf(this.get('object')) === 'instance'){
                    self.addObserver('object.' + self.get('meta.dataSource'), function(){
                        Ember.run.once(self, 'setCheckboxValue');
                    });
                }
            },

            init: function(){
                this._super();
                this.addObserverProperty();
            },

            willDestroyElement: function(){
                var self = this;
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
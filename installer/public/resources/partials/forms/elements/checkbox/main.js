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
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' id="' + inputId + '" name="' + name + '" value="1"/>';
                var label = '<label for="{{unbound view.inputId}}" unselectable="on" onselectstart="return false;"><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(hiddenInput + checkbox + label);
            }.property(),

            classNames: ['umi-element-checkbox'],

            inputId: function(){
                return 'input-' + this.get('elementId');
            }.property(),

            didInsertElement: function(){
                this._super();
                var self = this;
                if(Ember.typeOf(this.get('object')) === 'instance'){
                    var $el = this.$();
                    $el.on('change', 'input[type="checkbox"]', function(){
                        var isChecked = this.checked;
                        self.get('object').set(self.get('meta.dataSource'), isChecked);
                    });
                    self.addObserver('object.' + self.get('meta.dataSource'), function(){
                        $el.find('input[type="checkbox"]')[0].checked = self.get('object.' + self.get('meta.dataSource'));
                    });
                }
            },

            willDestroyElement: function(){
                var self = this;
                self.$().off('change');
                self.removeObserver('object.' + self.get('meta.dataSource'));
            }
        });
    };
});
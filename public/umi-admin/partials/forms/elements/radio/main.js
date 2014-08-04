define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.RadioElementView = Ember.View.extend({
            templateName: 'partials/radioElement',
            classNames: ['umi-element-radio-group'],

            addObserverProperty: function(){
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setSelectedRadioElement');
                });
            },

            setSelectedRadioElement: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "";
                    var radio = $el[0].querySelector('input[type="radio"][value="' + objectValue + '"]');
                    if(radio){
                        radio.checked = true;
                    }
                }
            },

            init: function(){
                this._super();
                Ember.warn('Field with the type of radio not supported lazy choices.', !this.get('meta.lazy'));

                if(Ember.typeOf(this.get('object')) === 'instance'){
                    this.addObserverProperty();
                }
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            radioElementView: Ember.View.extend({
                classNames: ['umi-element-radio'],

                template: function(){
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue;

                    if(Ember.typeOf(object) === 'instance'){
                        objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "";
                    } else{
                        objectValue = Ember.get(meta, 'value');
                    }

                    if(objectValue === value){
                        isChecked = true;
                    }
                    var radio = '<input type="radio" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(radio + label);
                }.property(),

                label: function(){
                    Ember.warn('For field with type of radio label not defined in choices.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),

                actions: {
                    change: function(){
                        var self = this;
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');

                        if(Ember.typeOf(object) === 'instance'){
                            Ember.set(object, propertyName, value);
                        } else{
                            var radio = this.$().find('input[type="radio"]');
                            if(radio.length){
                                radio[0].checked = true;
                            }
                        }
                    }
                }
            })
        });
    };
});
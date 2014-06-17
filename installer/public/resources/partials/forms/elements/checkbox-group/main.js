define(['App', 'text!./checkboxGroupElement.hbs', 'text!./checkboxGroupCollectionElement.hbs'], function(UMI, checkboxGroupElement, checkboxGroupCollectionElement){
    "use strict";

    return function(){
        UMI.CheckboxGroupElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(checkboxGroupElement),
            classNames: ['umi-element-checkbox-group']
        });

        UMI.CheckboxGroupCollectionElementView = Ember.View.extend({
            template: Ember.Handlebars.compile(checkboxGroupCollectionElement),
            classNames: ['umi-element-checkbox-group'],

            addObserverProperty: function(){
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function(){
                    Ember.run.once(self, 'setCheckboxesValue');
                });
            },

            setCheckboxesValue: function(){
                var self = this;
                var $el = this.$();
                if($el){
                    var checkboxes = $el.find('input[type="checkbox"]');
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "[]";
                    objectValue = JSON.parse(objectValue);
                    for(var i = 0; i < checkboxes.length; i++){
                        checkboxes[i].checked = objectValue.contains(checkboxes[i].value);
                    }
                }
            },

            init: function(){
                this._super();
                Ember.warn('Поле с типом checkboxGroup не поддерживает lazy choices.', !this.get('meta.lazy'));
                this.addObserverProperty();
            },

            willDestroyElement: function(){
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            checkboxElementView: Ember.View.extend({
                classNames: ['umi-element-checkbox'],
                template: function(){
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "[]";
                    objectValue = JSON.parse(objectValue);
                    if(objectValue.contains(value)){
                        isChecked = true;
                    }
                    var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(checkbox + label);
                }.property(),
                label: function(){
                    Ember.warn('Не задан label в choices поля с типом checkboxGroup.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),
                actions: {
                    change: function(){
                        var self = this;
                        var $el = this.$();
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');
                        var objectValue = Ember.get(object, propertyName) || "[]";
                        objectValue = JSON.parse(objectValue);

                        if(objectValue.contains(value)){
                            objectValue = objectValue.without(value);
                        } else{
                            objectValue.push(value);
                        }
                        objectValue.sort();
                        Ember.set(object, propertyName, JSON.stringify(objectValue));
                    }
                }
            })
        });
    };
});
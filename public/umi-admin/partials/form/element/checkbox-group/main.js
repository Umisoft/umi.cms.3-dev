define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormCheckboxGroupElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "checkboxGroupElement" object=view.object meta=view.meta}}')
        });

        UMI.FormCheckboxGroupElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            template: Ember.Handlebars.compile('{{view "checkboxGroupCollectionElement" object=view.object meta=view.meta}}')
        });

        UMI.CheckboxGroupElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup',
            classNames: ['umi-element-checkbox-group']
        });

        UMI.CheckboxGroupCollectionElementView = Ember.View.extend({
            templateName: 'partials/checkboxGroup/collectionElement',
            classNames: ['umi-element-checkbox-group'],

            addObserverProperty: function() {
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function() {
                    Ember.run.once(self, 'setCheckboxesValue');
                });
            },

            setCheckboxesValue: function() {
                var self = this;
                var $el = this.$();
                if ($el) {
                    var checkboxes = $el.find('input[type="checkbox"]');
                    var objectValue = this.get('object.' + self.get('meta.dataSource')) || "[]";
                    try {
                        objectValue = JSON.parse(objectValue);
                    } catch (error) {
                        error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = objectValue.contains(checkboxes[i].value);
                    }
                }
            },

            init: function() {
                this._super();
                Ember.warn('Field with type of checkboxGroup no supported lazy choices.', !this.get('meta.lazy'));
                this.addObserverProperty();
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            checkboxElementView: Ember.View.extend({
                classNames: ['umi-element-checkbox'],
                template: function() {
                    var self = this;
                    var object = self.get('parentView.object');
                    var meta = self.get('parentView.meta');
                    var name = Ember.get(meta, 'attributes.name');
                    var value = Ember.get(this, 'context.attributes.value');
                    var isChecked;
                    var objectValue = Ember.get(object, Ember.get(meta, 'dataSource')) || "[]";
                    try {
                        objectValue = JSON.parse(objectValue);
                    } catch (error) {
                        error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                        this.get('controller').send('backgroundError', error);
                    }
                    if (objectValue.contains(value)) {
                        isChecked = true;
                    }
                    var checkbox = '<input type="checkbox" ' + (
                        isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                    var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.label}}</label>';
                    return Ember.Handlebars.compile(checkbox + label);
                }.property(),
                label: function() {
                    Ember.warn('For the field with type of checkboxGroup label not defined in choices.', this.get('context.attributes.label'));
                    return this.get('context.attributes.label') || this.get('context.attributes.value');
                }.property('context.attributes.label'),
                actions: {
                    change: function() {
                        var self = this;
                        var $el = this.$();
                        var value = this.get('context.attributes.value');
                        var object = self.get('parentView.object');
                        var meta = self.get('parentView.meta');
                        var propertyName = Ember.get(meta, 'dataSource');
                        var objectValue = Ember.get(object, propertyName) || "[]";
                        try {
                            objectValue = JSON.parse(objectValue);
                        } catch (error) {
                            error.message = 'Incorrect value of field ' + propertyName + '. Expected array or null. ' + error.message;
                            this.get('controller').send('backgroundError', error);
                        }

                        if (objectValue.contains(value)) {
                            objectValue = objectValue.without(value);
                        } else {
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
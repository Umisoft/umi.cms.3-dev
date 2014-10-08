define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.FormCheckboxElementMixin = Ember.Mixin.create(UMI.FormElementMixin, {
            classNames: ['small-12', 'large-4'],

            layout: Ember.Handlebars.compile('<div class="umi-form-element-without-label">{{yield}}{{view.isRequired}}</div>'),

            template: Ember.Handlebars.compile('{{view "checkboxElement" object=view.object meta=view.meta}}')
        });

        UMI.FormCheckboxCollectionElementMixin = Ember.Mixin.create(UMI.FormCheckboxElementMixin, {
            template: Ember.Handlebars.compile('{{view "checkboxCollectionElement" object=view.object meta=view.meta}}')
        });

        UMI.CheckboxElementView = Ember.View.extend({
            template: function() {
                var self = this;
                var name = self.get('name');
                var attributeValue = self.get('attributeValue');
                var className = self.get('className');
                var isChecked = self.get('value');

                var hiddenInput = '<input type="hidden" name="' + name + '" value="0" />';
                var checkbox = '<input type="checkbox" ' + (isChecked ? "checked" :
                    "") + ' name="' + name + '" value="' + attributeValue + '" class="' + className + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(hiddenInput + checkbox + label);
            }.property(),

            name: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.name');
            }.property('meta.attributes.name'),

            value: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'value');
            }.property('meta.value'),

            attributeValue: function() {
                var meta = this.get('meta');
                return Ember.get(meta, 'attributes.value');
            }.property('meta.attributes.value'),

            classNames: ['umi-element-checkbox'],

            actions: {
                change: function() {
                    var $el = this.$();
                    var checkbox = $el.find('input[type="checkbox"]')[0];
                    checkbox.checked = !checkbox.checked;
                    $(checkbox).trigger("change");

                    if (this.get('objectNeedChange')) {
                        Ember.set(this.get('meta'), 'value', checkbox.checked ? 1 : 0);
                    }
                }
            }
        });

        UMI.CheckboxCollectionElementView = Ember.View.extend({
            template: function() {
                var self = this;
                var isChecked;
                var object = self.get('object');
                var meta = self.get('meta');
                var name = Ember.get(meta, 'attributes.name');
                var value = Ember.get(meta, 'attributes.value');

                isChecked = Ember.get(object, Ember.get(meta, 'dataSource'));

                var checkbox = '<input type="checkbox" ' + (
                    isChecked ? "checked" : "") + ' name="' + name + '" value="' + value + '"/>';
                var label = '<label unselectable="on" onselectstart="return false;" {{action "change" target="view"}}><span></span>{{view.meta.label}}</label>';
                return Ember.Handlebars.compile(checkbox + label);
            }.property(),

            classNames: ['umi-element-checkbox'],

            setCheckboxValue: function() {
                var self = this;
                var $el = this.$();
                if ($el) {
                    $el.find('input[type="checkbox"]')[0].checked = self.get('object.' + self.get('meta.dataSource'));
                }
            },

            addObserverProperty: function() {
                var self = this;
                self.addObserver('object.' + self.get('meta.dataSource'), function() {
                    Ember.run.once(self, 'setCheckboxValue');
                });
            },

            init: function() {
                this._super();
                this.addObserverProperty();
            },

            willDestroyElement: function() {
                var self = this;
                self.removeObserver('object.' + self.get('meta.dataSource'));
            },

            actions: {
                change: function() {
                    var self = this;
                    var $el = this.$();
                    var checkbox;
                    self.get('object').toggleProperty(self.get('meta.dataSource'));
                }
            }
        });
    };
});
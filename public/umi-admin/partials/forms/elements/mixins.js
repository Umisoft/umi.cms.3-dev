define(['App'], function(UMI) {
    'use strict';

    return function() {
        UMI.InputValidate = Ember.Mixin.create({
            /**
             * Определяет тип валидатора
             * @property validatorType
             * @optional
             */
            validatorType: null,

            /**
             * Метод вызывается при необходимости валидации поля
             * @method sendValidate
             * @optional
             */
            checkValidate: function() {},

            focusOut: function() {//TODO: Вынести тригеры из миксина. Удобнее будет управлять валидацией непосредственно из элемента
                this.validate();
            },

            /**
             * Метод вызывается для отмены валидатора
             */
            clearValidate: function() {},

            focusIn: function() {
                this.clearValidateError();
            },

            validate: function() {
                var self = this;
                var validatorType = this.get('validatorType');
                var property;
                var meta = self.get('meta');
                var validationError;

                if (validatorType === 'collection') {
                    property = Ember.get(meta, 'dataSource');
                    var object = self.get('object');
                    object.filterProperty(property);
                    object.validateProperty(property);
                } else {
                    UMI.validator.filterProperty(Ember.get(meta, 'value'), Ember.get(meta, 'filters'));
                    validationError = UMI.validator.validateProperty(Ember.get(meta, 'value'), Ember.get(meta, 'validators'));
                    validationError = validationError || [];
                    Ember.set(meta, 'errors', validationError);
                }
            },

            clearValidateError: function() {
                var self = this;
                var meta = self.get('meta');
                var dataSource = Ember.get(meta, 'dataSource');

                if (self.get('validatorType') === 'collection') {
                    var object = self.get('object');
                    object.clearValidateForProperty(dataSource);
                } else {
                    Ember.set(meta, 'errors', []);
                }
            },

            validateErrorsTemplate: function() {
                var validatorType = this.get('validatorType');
                var template;
                var propertyName;

                if (validatorType === 'collection') {
                    propertyName = this.get('meta.dataSource');
                    template = '{{#if view.object.validErrors.' + propertyName + '}}' +
                        '<small class="error">{{view.object.validErrors.' + propertyName + '}}</small>' +
                        '{{/if}}';
                } else {
                    template = '{{#if view.parentView.isError}}<small class="error">{{view.parentView.isError}}</small>{{/if}}';
                }

                return template;
            }
        });

        UMI.SerializedValue = Ember.Mixin.create({
            /**
             * Путь к изменяемому свойству
             * @abstract
             */
            path: null,
            /**
             * @property value
             */
            value: null,

            inputIsObservable: false,

            objectIsObservable: false,

            computeValue: function(computedValue, path, value) {
                Ember.set(computedValue, path, value);
                return computedValue;
            },

            setValueForObject: function() {
                var self = this;
                var value;
                var path;
                var computedValue;
                var selectedValue;
                var result = '';
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        value = self.get('value');
                        path = self.get('path');
                        value = Ember.isNone(value) ? '' : value;
                        computedValue = self.get('object.' + self.get('meta.dataSource')) || '';

                        if (value) {
                            if (computedValue) {
                                computedValue = JSON.parse(computedValue);
                            }
                            selectedValue = Ember.get(computedValue, path);
                            selectedValue = Ember.isNone(selectedValue) ? '' : selectedValue;
                            if (selectedValue !== value) {
                                if (value) {
                                    result = self.computeValue(computedValue, path, value);
                                    result = JSON.stringify(result);
                                }
                                self.get('object').set(self.get('meta.dataSource'), result);
                            }
                        } else {
                            if (computedValue !== value) {
                                self.get('object').set(self.get('meta.dataSource'), result);
                            }
                        }
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            setInputValue: function() {
                var self = this;
                var path = self.get('path');
                var computedValue = self.get('object.' + self.get('meta.dataSource'));
                var value = self.get('value');
                value = Ember.isNone(value) ? '' : value;
                var selectedValue;
                try {
                    if (computedValue) {
                        computedValue = JSON.parse(computedValue);
                        selectedValue = Ember.get(computedValue, path);
                        selectedValue = Ember.isNone(selectedValue) ? '' : selectedValue;
                        Ember.set(computedValue, path, selectedValue);

                        if (selectedValue !== value) {
                            self.set('value', selectedValue);
                        }
                    } else {
                        if (computedValue !== value) {
                            self.set('value', '');
                        }
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            init: function() {
                this._super();
                var computedValue;
                var self = this;
                var path = this.get('path');
                try {
                    if (Ember.typeOf(self.get('object')) === 'instance') {
                        computedValue = self.get('object.' + self.get('meta.dataSource')) || '{}';
                        computedValue = JSON.parse(computedValue);

                        self.set('value', Ember.get(computedValue, path) || '');

                        if (self.get('inputIsObservable')) {
                            self.addObserver('value', function() {
                                Ember.run.once(this, 'setValueForObject');
                            });
                        }

                        if (self.get('objectIsObservable')) {
                            self.addObserver('object.' + self.get('meta.dataSource'), function() {
                                Ember.run.once(self, 'setInputValue');
                            });
                        }

                    } else {
                        self.set('value', self.get('meta.value'));
                    }
                } catch (error) {
                    self.get('controller').send('backgroundError', error);
                }
            },

            willDestroyElement: function() {
                var self = this;
                if (Ember.typeOf(self.get('object')) === 'instance') {
                    self.removeObserver('object.' + self.get('meta.dataSource'));
                    self.removeObserver('value');
                }
            }
        });
    };
});
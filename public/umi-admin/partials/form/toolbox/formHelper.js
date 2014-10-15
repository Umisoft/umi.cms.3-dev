define(
    ['App'],
    function(UMI) {
        'use strict';

        return function() {
            UMI.FormHelper = {
                getNestedProperties: function(elements, ignoreTypes) {
                    var propertyNames = [];
                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    if (Ember.typeOf(ignoreTypes) !== 'array') {
                        ignoreTypes = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (!ignoreTypes.contains(Ember.get(elements[i], 'type'))) {
                            var dataSource = Ember.get(elements[i], 'dataSource');
                            if (dataSource) {
                                propertyNames.push(dataSource);
                            }
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedPropertyNames = this.getNestedProperties(nestedElements, ignoreTypes);
                            propertyNames = propertyNames.concat(nestedPropertyNames);
                        }
                    }

                    return propertyNames;
                },

                filterLazyProperties: function(elements) {
                    var lazyProperties = [];

                    if (Ember.typeOf(elements) !== 'array') {
                        Ember.warn('Wrong type argument: expected array.');
                        elements = [];
                    }

                    for (var i = 0; i < elements.length; i++) {
                        if (elements[i].lazy) {
                            lazyProperties.push(Ember.get(elements[i], 'dataSource'));
                        }

                        var nestedElements = Ember.get(elements[i], 'elements');

                        if (nestedElements) {
                            var nestedLazyProperties = this.filterLazyProperties(nestedElements);
                            lazyProperties = lazyProperties.concat(nestedLazyProperties);
                        }
                    }

                    return lazyProperties;
                },

                fillMeta: function(meta, object) {
                    var _fillMeta = function(elements) {
                        var dataSource;
                        for (var i = 0; i < elements.length; i++) {
                            dataSource = elements[i].dataSource;
                            if (dataSource) {
                                elements[i].value = object.get(dataSource);
                            }

                            if (elements[i].hasOwnProperty('elements')) {
                                _fillMeta(elements[i].elements);
                            }
                        }
                    };

                    var elements = Ember.get(meta, 'elements');

                    _fillMeta(elements);
                    return meta;
                },

                getSlug: function(displayName) {
                    if (Ember.typeOf(displayName) !== 'string') {
                        Ember.warn('Wrong argument type. Expected array.');
                        displayName = '';
                    }

                    var slug = window.URLify(displayName);
                    return slug;
                }
            };
        };
    }
);
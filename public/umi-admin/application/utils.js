define(['Modernizr'], function(Modernizr) {
    "use strict";

    return function(UMI) {
        /**
         * Umi Utilities Class.
         * @class Utils
         * @static
         */
        UMI.Utils = {};

        UMI.Utils.htmlEncode = function(str) {
            str = str + "";
            return str.replace(/[&<>"']/g, function($0) {
                return "&" + {"&": "amp", "<": "lt", ">": "gt", '"': "quot", "'": "#39"}[$0] + ";";
            });
        };

        UMI.Utils.replacePlaceholder = function(object, pattern) {
            var deserialize;

            if (!object) {
                return pattern;
            }

            deserialize = pattern.replace(/{\w+}/g, function(key) {
                if (key) {
                    key = key.slice(1, -1);
                }
                return Ember.get(object, key) || key;
            });
            return deserialize;
        };

        UMI.Utils.objectsMerge = function(objectBase, objectProperty) {
            Ember.warn('Incorrect type of arguments. ObjectsMerge method expects arguments of type "object"', Ember.typeOf(objectBase) === 'object' && Ember.typeOf(objectProperty) === 'object');
            for (var key in objectProperty) {
                if (objectProperty.hasOwnProperty(key)) {
                    objectBase[key] = objectProperty[key];
                }
            }
        };

        UMI.Utils.getStringValue = function(prop) {
            var property;
            var properties;
            var value;
            switch (Ember.typeOf(prop)) {
                case 'string':
                    value = prop;
                    break;
                case 'array':
                    value = prop.join(',');
                    break;
                case 'object':
                    properties = [];
                    for (property in prop) {
                        if (prop.hasOwnProperty(property)) {
                            properties.push(UMI.Utils.getStringValue(prop[property]));
                        }
                    }
                    value = properties;
                    break;
            }
            return value;
        };

        /**
         * Local Storage
         */
        UMI.Utils.LS = {
            store: localStorage,
            init: function() {
                if (Modernizr.localstorage) {
                    if (!localStorage.getItem("UMI")) {
                        localStorage.setItem("UMI", JSON.stringify({}));
                    }
                } else {
                    //TODO: Не обрабатывается сутуация когда Local Storage не поддерживается
                    this.store = {'UMI': JSON.stringify({})};
                }
            },

            get: function(key) {
                var data = JSON.parse(this.store.UMI);
                return Ember.get(data, key);
            },

            set: function(keyPath, value) {
                var data = JSON.parse(this.store.UMI);
                var keys = keyPath.split('.');
                var i = 0;
                var setNestedProperty = function getNestedProperty(obj, key, value) {
                    if (!obj.hasOwnProperty(key)) {
                        obj[key] = {};
                    }
                    if (i < keys.length - 1) {
                        i++;
                        getNestedProperty(obj[key], keys[i], value);
                    } else {
                        obj[key] = value;
                    }
                };
                setNestedProperty(data, keys[0], value);
                if (Modernizr.localstorage) {
                    this.store.setItem('UMI', JSON.stringify(data));
                } else {
                    this.store.UMI = JSON.stringify(data);
                }
            }
        };

        Ember.Handlebars.registerHelper('filterClassName', function(value, options) {
            value = Ember.Handlebars.helpers.unbound.apply(this, [value, options]);
            value = value.replace(/\./g, '__');//TODO: replace all deprecated symbols
            return value;
        });

        UMI.Utils.LS.init();

    };
});
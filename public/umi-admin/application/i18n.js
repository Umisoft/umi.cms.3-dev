define([], function() {
    'use strict';
    return function(UMI) {
        Ember.Handlebars.helper('i18n', function(label, namespace) {
                if (Ember.typeOf(namespace) !== 'string') {
                    namespace = undefined;
                }
                var translateLabel = UMI.i18n.getTranslate(label, namespace);
                return translateLabel ? translateLabel : label;
            });

        UMI.i18n = Ember.Object.extend({
            dictionary: {},
            setDictionary: function(translate, namespace) {
                var dictionary = this.get('dictionary');
                var namespaceDictionary;
                if (namespace && namespace) {
                    namespaceDictionary = Ember.typeOf(dictionary[namespace]) === 'object' ? dictionary[namespace] : {};
                    Ember.set(dictionary, namespace, namespaceDictionary);
                }
                for (var key in translate) {
                    if (translate.hasOwnProperty(key)) {
                        if (namespace) {
                            Ember.set(Ember.get(dictionary, namespace), key, translate[key]);
                        } else {
                            Ember.set(dictionary, key, translate[key]);
                        }

                    }
                }
            },
            getTranslate: function(label, componentPath) {
                var path = 'dictionary.' + (componentPath ? componentPath + '.' : '') + label;
                var translate = this.get(path);
                return translate ? translate : label;
            }
        }).create({});

        UMI.i18nInterface = Ember.Mixin.create({
            /**
             * namespace, например имя контола реализующего итерфейс i18n
             * @property context
             * @abstract
             */
            dictionaryNamespace: null,
            /**
             * Словарь для контрола
             * @property localDictionary
             * @abstract
             */
            localDictionary: null,
            setDictionary: function() {
                UMI.i18n.setDictionary(this.get('localDictionary'), this.get('dictionaryNamespace'));
            },
            init: function() {
                this._super();
                this.setDictionary();
            }
        });
    };
});
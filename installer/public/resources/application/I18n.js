define([], function(){
    'use strict';
    return function(UMI){
        Ember.Handlebars.registerHelper('I18n',
            function(label, options){
                if(Ember.typeOf(options) !== 'string'){
                    options = undefined;
                }
                var translateLabel = UMI.I18n.getTranslate(label, options);
                return translateLabel ? translateLabel : label;
            }
        );

        UMI.I18n = Ember.Object.extend({
            setDictionary: function(translate){
                var dictionary = this.get('dictionary');
                for(var key in translate){
                    if(translate.hasOwnProperty(key)){
                        Ember.set(dictionary, key, translate[key]);
                    }
                }
            },
            dictionary: {},
            locale: function(){
                return window.UmiSettings.locale;
            }.property(),
            getTranslate: function(label, component){
                var locale = this.get('locale');
                component = component || 'layout';
                var translate = this.get('dictionary.' + component + '.' + locale + '.' + label);
                return translate ? translate : label;
            }
        }).create({});
    };
});
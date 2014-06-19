define([], function(){
    'use strict';
    return function(UMI){
        Ember.Handlebars.registerHelper('I18n',
            function(label, path){
                if(Ember.typeOf(path) !== 'string'){
                    path = undefined;
                }
                var translateLabel = UMI.I18n.getTranslate(label, path);
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
            getTranslate: function(label, componentPath){
                var locale = this.get('locale');
                componentPath = componentPath || 'layout';

                var translate = this.get('dictionary.' + componentPath + '.' + locale + '.' + label);
                return translate ? translate : label;
            }
        }).create({});
    };
});
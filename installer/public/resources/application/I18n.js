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
            getTranslate: function(label, componentPath){
                var path = 'dictionary.' + (componentPath ? componentPath + '.' : '') + label ;
                var translate = this.get(path);
                return translate ? translate : label;
            }
        }).create({});
    };
});
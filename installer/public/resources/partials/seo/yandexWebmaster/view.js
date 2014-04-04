define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.SeoYandexWebmasterView = Ember.View.extend({
            templateName: 'seoYandexWebmaster',
            didInsertElement: function(){
                console.log('yandexWebmaster');
            }
        });
    };
});
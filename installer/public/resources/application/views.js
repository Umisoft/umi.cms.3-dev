define([], function(){
    'use strict';

    return function(UMI){

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            didInsertElement: function(){
                if(window.applicationLoading){
                    window.applicationLoading.resolve();
                }
            }
        });
    };
});
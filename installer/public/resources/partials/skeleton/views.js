define([], function(){
    'use strict';

    return function(UMI){

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height']
        });
    };
});
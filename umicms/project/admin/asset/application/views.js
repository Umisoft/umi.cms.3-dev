define([], function(){
    'use strict';

    return function(UMI){

        UMI.ApplicationView = Ember.View.extend({
            classNames: ['umi-main-view', 's-full-height'],
            didInsertElement: function(){
                $('body').removeClass('loading');
                if(window.applicationLoading){
                    window.applicationLoading.resolve();
                }
            }
        });

        UMI.ComponentView = Ember.View.extend({
            classNames: ['umi-content', 's-full-height'],

            filtersView: Ember.View.extend({
                classNames: ['umi-tree-control-filters']
            })
        });
    };
});
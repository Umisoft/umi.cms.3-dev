define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.TableView = Ember.View.extend({
            templateName: 'table',
            classNames: ['umi-table-ajax'],
            didInsertElement: function(){

            }
        });
    };
});
define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.DockController = Ember.ArrayController.extend({
            content: [],
            sortAscending: true,
            sortProperties: ['id']
        });
    };
});
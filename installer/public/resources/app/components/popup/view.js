define(['App'], function(UMI){
    'use strict';

    return function(){
        UMI.PopupView = Ember.View.extend({
            actions: {
                close: function(){
                    console.log('popupClose');
                }
            },

            didInsertElement: function(){
                console.log('popupShow');
            }
        });
    };
});
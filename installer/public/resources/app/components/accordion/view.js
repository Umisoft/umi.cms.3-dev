define(['App'], function(UMI){
    'use strict';
    return function(){

        UMI.AccordionView = Ember.View.extend({
            didInsertElement: function(){
                $('span').mousedown(function(){
                    $('.umi-accordion-trigger').removeClass('active');
                    $(this).siblings('.umi-accordion-trigger').addClass('active');
                });
            }
        });
    };
});
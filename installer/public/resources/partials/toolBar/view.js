define(['App'], function(UMI){
    'use strict';

    return function(){

        UMI.ToolBarView = Ember.View.extend({
            /**
             * @property templateName
             */
            templateName: 'toolBar',
            /**
             * @property leftSide
             */
            leftSide: null,
            /**
             * @property rightSide
             */
            rightSide: null
        });
    }
});
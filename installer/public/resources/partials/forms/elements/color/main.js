define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.ColorElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-color'],
            type: "color"
        });
    };
});
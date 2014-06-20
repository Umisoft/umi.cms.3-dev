define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.EmailElementView = UMI.TextElementView.extend({
            classNames: ['umi-element-email'],
            type: "email"
        });
    };
});
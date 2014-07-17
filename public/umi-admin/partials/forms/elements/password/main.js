define(['App'], function(UMI){
    "use strict";

    return function(){
        UMI.PasswordElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-password'],
            type: 'text'
        });
    };
});
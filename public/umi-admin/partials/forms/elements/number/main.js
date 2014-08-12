define(['App'], function(UMI) {
    "use strict";

    return function() {
        UMI.NumberElementView = UMI.TextElementView.extend({
            classNames: ['umi-element', 'umi-element-number'],
            type: 'number'
        });
    };
});